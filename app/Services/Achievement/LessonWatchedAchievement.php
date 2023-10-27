<?php

namespace App\Services\Achievement;

use App\Contracts\Achievement\CanBeUnlocked;
use App\Contracts\Achievement\IsAchievableViaNewMilestone;
use App\Models\Achievement;
use App\Models\User;
use App\Services\User\UserAchievementService;

class LessonWatchedAchievement implements IsAchievableViaNewMilestone, CanBeUnlocked
{
    protected $user_achievement_service;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct(UserAchievementService $user_achievement_service)
    {
      $this->user_achievement_service = $user_achievement_service;
    }

  
  /**
   * @inheritdoc
   */
  public function hasReachedNewMilestone(User $user): bool
  {
    $lessons_watched = $user->watched->count();

    $achievement = Achievement::where('action', LESSON_WATCHED)
        ->where('action_count_required', $lessons_watched)
        ->first();
    
    if (!isset($achievement)) {
        return false;
    }

    if ($this->user_achievement_service->userAlreadyHasAchievement($user, $achievement->id)) {
        return false;
    }

    return true;
  }


  /**
   * @inheritdoc
   */
  public function unlockForUser(User $user): void
  {
    $lessons_watched = $user->watched->count();

    $nextAchievement = Achievement::where('action', LESSON_WATCHED)
        ->where('action_count_required', '>=', $lessons_watched)
        ->orderBy('action_count_required')
        ->first();

    $this->user_achievement_service->unlockAchievement($user->id, $nextAchievement->id);
  }
}
