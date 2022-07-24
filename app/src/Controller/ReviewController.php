<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Review;
use App\Entity\User;
use App\Form\ReviewType;
use Doctrine\ORM\EntityNotFoundException;
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
        return $this->handleView($this->view($reviews, Response::HTTP_OK));
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

        //submit data
        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {
            //verify if company exists (not sure if this is the efficient way or if should be through company repository()
            $company = Company::verifyCompany($this->doctrine->getRepository(Company::class), $data['company']);
            if (!$company)
                return $this->handleView($this->view(['data' => ['msg' => 'Company not found']], Response::HTTP_BAD_REQUEST));

            //verify if user exists (not sure if this is the efficient way or if should be through user repository()
            $user = User::verifyUser($this->doctrine->getRepository(User::class), $data['user']);
            if (!$user)
                return $this->handleView($this->view(['data' => ['msg' => 'User not found']], Response::HTTP_BAD_REQUEST));

            $em = $this->doctrine->getManager();
            $em->persist($review);
            $em->flush();

            $reviewsRepository = $this->doctrine->getRepository(Review::class);
            $company->updateAvgRating($reviewsRepository);
            $em->persist($company);
            $em->flush();
            return $this->handleView($this->view(['data' => ['msg' => 'Review Created successfully']], Response::HTTP_CREATED));
        } else {
            // $aux = [];
            // $errors = $form->getErrors(true, false);
            // foreach ($errors as $error) {
            //     $aux[] = $error->current()->getMessage();
            // }
            //return array('status' =>  Response::HTTP_BAD_REQUEST, 'data' => $aux);
            return $this->handleView($this->view(['data' => $form->getErrors()], Response::HTTP_BAD_REQUEST));
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
            //verify if company exists (not sure if this is the efficient way or if should be through company repository()
            $company = Company::verifyCompany($this->doctrine->getRepository(Company::class), $data['company_id']);
            if (!$company)
                return $this->handleView($this->view(['data' => ['msg' => 'Company not found']], Response::HTTP_BAD_REQUEST));

            $reviewRepository = $this->doctrine->getRepository(Review::class);
            $highestLowRating = Review::getHighestLowestRatingReview($data['company_id'], $reviewRepository);

            if ($highestLowRating) {
                return $this->handleView($this->view(['data' => $highestLowRating], Response::HTTP_OK));
            } else {
                return $this->handleView($this->view(['data' => ['msg' => 'Reviews not found']], Response::HTTP_NO_CONTENT));
            }
        } else
            return $this->handleView($this->view(['data' => ['msg' => 'data not found']], Response::HTTP_BAD_REQUEST));
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
            //verify if company exists (not sure if this is the efficient way or if should be through company repository()
            $company = Company::verifyCompany($this->doctrine->getRepository(Company::class), $data['company_id']);
            if (!$company)
                return $this->handleView($this->view(['data' => ['msg' => 'Company not found']], Response::HTTP_BAD_REQUEST));

            //users who reviewed given company
            $usersWhoReviewed = $this->doctrine->getRepository(Review::class)->findDifferentUsersWithCompanyId($data['company_id']);
            //other companies reviewed by those users
            $companiesReviewed = $this->doctrine->getRepository(Review::class)->findDifferentCompaniesWithUsers($usersWhoReviewed, $data['company_id']);

            if ($companiesReviewed) {
                return $this->handleView($this->view(['data' => $companiesReviewed], Response::HTTP_OK));
            } else {
                return $this->handleView($this->view(['data' => ['msg' => 'Reviews not found']], Response::HTTP_NO_CONTENT));
            }
        } else
            return $this->handleView($this->view(['data' => ['msg' => 'data not found']], Response::HTTP_BAD_REQUEST));
    }
}
