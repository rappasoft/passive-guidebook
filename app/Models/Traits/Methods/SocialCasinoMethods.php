<?php

namespace App\Models\Traits\Methods;

trait SocialCasinoMethods
{
    public function commentableName(): string
    {
        return $this->name;
    }

    public function commentUrl(): string
    {
        return route('passive.social-casinos.show', ['socialCasino' => $this]);
    }
}
