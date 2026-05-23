<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

#[Signature('admin:create {name : The user\'s full name} {email : The user\'s email address}')]
#[Description('Create or update an admin user. Admin access is granted when the email appears in ADMIN_EMAILS.')]
class CreateAdminUser extends Command
{
    public function handle(): int
    {
        $name = trim((string) $this->argument('name'));
        $email = strtolower(trim((string) $this->argument('email')));

        $validator = Validator::make(
            ['name' => $name, 'email' => $email],
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email:rfc'],
            ],
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $message) {
                $this->error($message);
            }

            return self::FAILURE;
        }

        $existing = User::query()->where('email', $email)->first();

        if ($existing && ! $this->confirm("A user with {$email} already exists. Update name and reset password?", default: false)) {
            $this->warn('Aborted.');

            return self::FAILURE;
        }

        $plain = (string) $this->secret('Password (min 12 characters)');

        if (strlen($plain) < 12) {
            $this->error('Password must be at least 12 characters.');

            return self::FAILURE;
        }

        $confirmation = (string) $this->secret('Confirm password');

        if ($plain !== $confirmation) {
            $this->error('Passwords do not match.');

            return self::FAILURE;
        }

        $user = $existing ?? new User;
        $user->forceFill([
            'name' => $name,
            'email' => $email,
            'password' => $plain,
            'email_verified_at' => $user->email_verified_at ?? now(),
        ])->save();

        $this->info(sprintf('%s admin user "%s" (%s).', $existing ? 'Updated' : 'Created', $user->name, $user->email));

        $adminEmails = array_map('strtolower', config('admin.emails', []));

        if (! in_array($email, $adminEmails, true)) {
            $this->newLine();
            $this->warn('This email is not in ADMIN_EMAILS - the user can sign in but will NOT have admin access.');
            $this->line('Add it to .env to grant admin privileges:');
            $this->line(sprintf('  ADMIN_EMAILS=%s', implode(',', array_filter([...$adminEmails, $email]))));
        } else {
            $this->info('Email is in ADMIN_EMAILS - admin access is active.');
        }

        return self::SUCCESS;
    }
}
