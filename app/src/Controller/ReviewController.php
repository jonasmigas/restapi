<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Review;
use App\Form\ReviewType;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * Review controller.
 * @Route("/api", name="api_")
 */
class ReviewController extends AbstractFOSRestController
{
    public function __construct(private ManagerRegistry $doctrine)
    {
    }
    /**
     * Lists all Reviews.
     * @Rest\Get("/reviews")
     *
     * @return Response
     */
    public function getReviewsAction()
    {
        $reviewRepository = $this->doctrine->getRepository(Review::class);
        $reviews = $reviewRepository->findall();
        return $this->handleView($this->view($reviews));
    }

    /**
     * Create Review.
     * @Rest\Post("/submitreview")
     *
     * @return Response
     */
    public function postReviewAction(Request $request)
    {
        $review = new Review();
        $form = $this->createForm(ReviewType::class, $review);
        $data = json_decode($request->getContent(), true);
        //confirm if companyExists
        $companyRepository = $this->doctrine->getRepository(Company::class);

        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->doctrine->getManager();
            $em->persist($review);
            $em->flush();

            $companyId = $review->getCompany();
            if ($companyId) {
                $company = $companyRepository->find($companyId);
                $reviewsRepository = $this->doctrine->getRepository(Review::class);
                $company->updateAvgRating($reviewsRepository);
                $em->persist($company);
                $em->flush();
                return array('status' =>  Response::HTTP_CREATED, 'data' => ['msg' => 'Review Created successfully']);
            } else
                return array('status' =>  Response::HTTP_CREATED, 'data' => ['msg' => 'Company not found']);
        }
        return $this->handleView($this->view($form->getErrors(), Response::HTTP_BAD_REQUEST));
    }

    /**
     * Retrieve the highest and lowest rating review for agiven company.
     * @Rest\Post("/ratinghighlow")
     *
     * @return Response
     */
    public function postHighestLowestRatingAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        if (isset($data['company_id']) && $data) {
            $reviewRepository = $this->doctrine->getRepository(Review::class);
            $highestLowRating = Review::getHighestLowestRatingReview($data['company_id'], $reviewRepository);

            if ($highestLowRating) {
                return array('status' =>  Response::HTTP_OK, 'data' => $highestLowRating);
            } else {
                return array('status' => Response::HTTP_NO_CONTENT, 'data' => ['msg' => 'Company not found']);
            }
        } else
            return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_BAD_REQUEST));
    }

    /**
     * "users who reviewed this company also reviewed" functionality: Given a particular company, list other companies that other users also reviewed
     * @Rest\Post("/userswhoreviewed")
     *
     * @return Response
     */
    public function postUserswhoReviewedAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        if (isset($data['company_id']) && $data) {
            //users who reviewed given company
            $usersWhoReviewed = $this->doctrine->getRepository(Review::class)->findDifferentUsersWithCompanyId($data['company_id']);
            //other companies reviewed by those users
            $companiesReviewed = $this->doctrine->getRepository(Review::class)->findDifferentCompaniesWithUsers($usersWhoReviewed, $data['company_id']);

            if ($companiesReviewed) {
                return array('status' => Response::HTTP_OK, 'data' => $companiesReviewed);
            } else {
                return array('status' => Response::HTTP_NO_CONTENT, 'data' => ['msg' => 'Company not found']);
            }
        } else
            return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_BAD_REQUEST));
    }
}
