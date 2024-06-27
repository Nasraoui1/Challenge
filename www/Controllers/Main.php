<?php

namespace App\Controller;

use App\Core\View;
use App\Models\User;
use App\Models\Article;
use App\Models\Comment;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Media;
use App\Models\ActivityLog;

class Main
{
    public function home()
    {
        $view = new View("Main/home", "Back");
        $view->render();
    }

    public function logout()
    {
        // Déconnexion
        // Redirection
    }

    public function dashboard()
    {
        try {
            $userModel = new User();
            $articleModel = new Article();
            $commentModel = new Comment();
            $categoryModel = new Category();
            $tagModel = new Tag();
            $mediaModel = new Media();
            $activityLogModel = new ActivityLog();

            $totalUsers = $userModel->countAll();
            $verifiedUsers = $userModel->countVerified();
            $recentUsers = $userModel->getRecent(5);

            $totalArticles = $articleModel->countAll();
            $publishedArticles = $articleModel->countByStatus('published');
            $draftArticles = $articleModel->countByStatus('draft');
            $recentArticles = $articleModel->getRecent(5);

            $totalComments = $commentModel->countAll();
            $recentComments = $commentModel->getRecent(5);

            $totalCategories = $categoryModel->countAll();
            $totalTags = $tagModel->countAll();

            $totalMedia = $mediaModel->countAll();
            $recentMedia = $mediaModel->getRecent(5);

            $recentActivities = $activityLogModel->getRecent(5);

            $view = new View("dashboard", "base");
            $view->assign("totalUsers", $totalUsers);
            $view->assign("verifiedUsers", $verifiedUsers);
            $view->assign("recentUsers", $recentUsers);
            $view->assign("totalArticles", $totalArticles);
            $view->assign("publishedArticles", $publishedArticles);
            $view->assign("draftArticles", $draftArticles);
            $view->assign("recentArticles", $recentArticles);
            $view->assign("totalComments", $totalComments);
            $view->assign("recentComments", $recentComments);
            $view->assign("totalCategories", $totalCategories);
            $view->assign("totalTags", $totalTags);
            $view->assign("totalMedia", $totalMedia);
            $view->assign("recentMedia", $recentMedia);
            $view->assign("recentActivities", $recentActivities);
            $view->render();
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
