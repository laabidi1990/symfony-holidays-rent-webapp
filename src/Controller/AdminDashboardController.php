<?php

namespace App\Controller;

use App\Service\StatsService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function index(StatsService $statsService)
    {
        $stats = $statsService->getStats();
        $bestAds = $statsService->getAdsStats(5, "DESC");
        $worstAds = $statsService->getAdsStats(5, "ASC");

        return $this->render('admin/dashboard/index.html.twig', [
            'stats' => $stats,
            'bestAds' => $bestAds,
            'worstAds' => $worstAds,
        ]);
    }
}
