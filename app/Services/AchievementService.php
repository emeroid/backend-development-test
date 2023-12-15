<?php

namespace App\Services;

use App\Models\User;
use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Models\Badge;
use App\Models\Achievement;

class AchievementService
{
    
    /**
     * Unlock achievements for watching lessons.
     * 
     * @param User $user
     * @param string $achievementType
    */
    public function unlockAchievement(User $user, string $achievementType)
    {
        // Determine the achievement name based on the achievement type and count
        $count = $this->getAchievementCount($user, $achievementType);
        $achievementName = $this->getAchievementName($achievementType, $count);
    
        // Check if the achievement is not unlocked
        if (!$this->isAchievementUnlocked($user, $achievementName)) {
            // Create a new achievement for the user
            $user->achievements()->create(['name' => $achievementName]);
    
            // Fire AchievementUnlocked event
            event(new AchievementUnlocked($achievementName, $user));
    
            // Check if a new badge is unlocked
            $this->checkAndUnlockBadge($user);
        }
    }

    /**
     * Get the dynamic achievement count based on the achievement type.
     *
     * @param User $user
     * @param string $achievementType
     * @return int
     */
    protected function getAchievementCount(User $user, string $achievementType)
    {
        switch ($achievementType) {
            case 'lessons_watched':
                return $user->watched()->wherePivot('watched', true)->count();
            case 'comments_written':
                return $user->comments()->count();
            // Add more cases for other achievement types as needed
            default:
                return 0;
        }
    }


    /**
     * Get the dynamic achievement name based on the achievement type and count.
     *
     * @param string $achievementType
     * @param int $count
     * @return string
    */
    protected function getAchievementName(string $achievementType, int $count)
    {
        // Define the achievement names and their corresponding thresholds
        $achievementNames = config("achievments");

        // Use the achievement name corresponding to the threshold
        return $achievementNames[$achievementType][$count] ?? '';
    }


    /**
     * Check if an achievement is already unlocked for a user.
     *
     * @param User $user
     * @param string $achievementName
     * @return bool
     */
    protected function isAchievementUnlocked(User $user, string $achievementName)
    {
        return $user->achievements()->where('name', $achievementName)->exists();
    }

    /**
     * Check and unlock a badge for a user.
     *
     * @param User $user
     */
    protected function checkAndUnlockBadge(User $user)
    {
        $unlockedAchievements = $this->getUnlockedAchievements($user);
        
        $nextBadge = null;
        $availableBadges = Badge::availableBadges();

        foreach ($availableBadges as $badgeName) {
            $badge = Badge::where('name', $badgeName)->first();

            if ($badge && count($unlockedAchievements) >= $badge->points) {
                $nextBadge = $badgeName;
                break;
            }
        }

        // Check if a new badge is unlocked
        if ($nextBadge !== null) {
            // Attach the badge to the user
            $user->badges()->attach($badge->id);

            // Fire BadgeUnlocked event
            event(new BadgeUnlocked($nextBadge, $user));
        }
    }

    /**
     * get unlock achievment for a user.
     *
     * @param User $user
     */
    public function getUnlockedAchievements(User $user): array
    {
        return $user->achievements->pluck('name')->toArray();
    }

    /**
     * Get the current badge for a user.
     *
     * @param User $user
     * @return string
     */
    public function getCurrentBadge(User $user): string
    {
        // Get the user's current badge from the database
        $currentBadge = $user->badges;

        return $currentBadge ? $currentBadge->name : null;
    }

    public function getNextBadge($user)
    {
        // Get the user's current badge from the database
        $currentBadge = $this->getCurrentBadge($user);

        // Get all available badges from the database
        $availableBadges = Badge::availableBadges();
 
        // Find the next achievable badge based on the current badge
        $nextBadge = null;
        $currentBadgeIndex = array_search($currentBadge, $availableBadges);
 
        if ($currentBadgeIndex !== false && isset($availableBadges[$currentBadgeIndex + 1])) {
            $nextBadge = $availableBadges[$currentBadgeIndex + 1];
        }
 
        return $nextBadge;
    }

    /**
     * Get the next available achievements for a user.
     *
     * @param User $user
     * @return array
    */
    public function getNextAvailableAchievements(User $user): array
    {
        $unlockedAchievements = $this->getUnlockedAchievements($user);
        $allAchievements = Achievement::availableAchievements();

        return array_diff($allAchievements, $unlockedAchievements);
    }

    /**
     * Get the next available achievements for a user.
     *
     * @param User $user
     * @return array
    */
    public function getRemainingToUnlockNextBadge($user)
    {
        $achievementsNeededForNextBadge = Badge::availableBadges();

        $unlockedAchievementsCount = count($this->getUnlockedAchievements($user));
        $currentBadge = $this->getCurrentBadge($user);

        return max(0, $achievementsNeededForNextBadge[$currentBadge] - $unlockedAchievementsCount);
    }

}
