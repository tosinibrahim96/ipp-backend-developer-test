<?php

namespace App\Events;


use App\Models\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class AchievementUnlocked
{
    use Dispatchable, SerializesModels;

    public $achievement_name, $user;

    /**
     * Create a new event instance.
     *
     * @param string $achievement_name
     * @param User $user
     * @return void
     */
    public function __construct(string $achievement_name, User $user)
    {
        $this->achievement_name = $achievement_name;
        $this->user = $user;
    }
}
