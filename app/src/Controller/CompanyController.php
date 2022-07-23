<?php

namespace App\Controller;

use App\Entity\Company;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Company controller.
 * @Route("/api", name="api_")
 */
class CompanyController extends AbstractFOSRestController
{
    public function __construct(private ManagerRegistry $doctrine)
    {
    }
    /**
     * Lists all Companies.
     * @Rest\Get("/companies")
     *
     * @return Response
     */
    public function getCompanyAction()
    {
        $repository = $this->doctrine->getRepository(Company::class);
        $companies = $repository->findall();
        return $this->handleView($this->view($companies));
    }
    /**
     * Lists top 10 Companies.
     * @Rest\Get("/topcompanies")
     *
     * @return Response
     */
    public function getTopcompanyAction()
    {
        $repository = $this->doctrine->getRepository(Company::class);
        $companies = $repository->findall();
        $topCompanies = Company::getTopCompanies($companies);
        if ($topCompanies && sizeof($topCompanies) > 0)
            return array('status' =>  Response::HTTP_OK, 'data' => $topCompanies);
        else
            return array('status' =>  Response::HTTP_OK, 'data' => ['msg' => 'No companies found']);
    }
}
