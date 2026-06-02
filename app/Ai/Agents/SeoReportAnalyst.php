<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Attributes\Provider;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Enums\Lab;
use Laravel\Ai\Promptable;
use Stringable;

#[Provider(Lab::OpenAI)]
class SeoReportAnalyst implements Agent, HasStructuredOutput
{
    use Promptable;

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return <<<'PROMPT'
        You are a senior SEO consultant writing a concise, honest audit for the owner of a
        small American business. You are given a JSON object with the visitor's industry and
        the on-page signals our crawler extracted from their website. Base every conclusion
        ONLY on those signals — never invent data, rankings, or traffic you were not given.

        Produce a practical report the owner can act on today, written in plain language with
        no jargon. Prioritise fixes by impact.

        For the Google Business Profile section, infer best-effort whether the business likely
        already has a profile from the contact/NAP signals (phone links, address hints, local
        schema). You cannot see Google directly, so frame it as likely/uncertain:
        - If a profile is likely MISSING: explain, in steps, how to create and verify one.
        - If a profile is likely PRESENT: give a checklist to optimise it, and stress that the
          business name, address, and phone number on the profile must EXACTLY match what is on
          the website (character for character), plus add photos, set hours and categories, and
          request reviews.

        Tailor industry_tips to the provided industry. Keep the overall score honest: a bare or
        broken site should score low; a well-optimised one should score high.
        PROMPT;
    }

    /**
     * The structured JSON shape the model must return.
     *
     * @return array<string, mixed>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'score' => $schema->integer()->min(0)->max(100)
                ->description('Overall SEO health from 0-100.')->required(),
            'summary' => $schema->string()
                ->description('Two or three sentences summarising the findings.')->required(),
            'sections' => $schema->array()->items(
                $schema->object([
                    'title' => $schema->string()->required(),
                    'items' => $schema->array()->items(
                        $schema->object([
                            'title' => $schema->string()->required(),
                            'priority' => $schema->string()->enum(['high', 'medium', 'low'])->required(),
                            'category' => $schema->string()->enum(['on_page', 'technical', 'content', 'local', 'trust'])->required(),
                            'detail' => $schema->string()->description('A specific, actionable instruction.')->required(),
                        ])
                    )->required(),
                ])
            )->required(),
            'industry_tips' => $schema->array()->items(
                $schema->object([
                    'title' => $schema->string()->required(),
                    'detail' => $schema->string()->required(),
                ])
            )->required(),
            'google_business_profile' => $schema->object([
                'likely_present' => $schema->boolean()->required(),
                'headline' => $schema->string()->required(),
                'steps' => $schema->array()->items(
                    $schema->object([
                        'title' => $schema->string()->required(),
                        'detail' => $schema->string()->required(),
                    ])
                )->required(),
            ])->required(),
        ];
    }
}
