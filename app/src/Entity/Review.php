<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="review")
 * @ORM\Entity(repositoryClass="App\Repository\ReviewRepository")
 */
class Review
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $review_id;
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(
     *  min = 10,
     *  max = 200,
     *  minMessage = "Your first name must be at least {{ limit }} characters long",
     *  maxMessage = "Your first name cannot be longer than {{ limit }} characters"
     * )
     */
    private $title;
    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $user;
    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $company;
    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     * @Assert\Range(
     *  min = 0,
     *  max = 5,
     *  notInRangeMessage = "It must be between {{ min }} and {{ max }}",
     * )
     */
    private $culture;
    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     * @Assert\Range(
     *  min = 0,
     *  max = 5,
     *  notInRangeMessage = "It must be between {{ min }} and {{ max }}",
     * )
     */
    private $management;
    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     * @Assert\Range(
     *  min = 0,
     *  max = 5,
     *  notInRangeMessage = "It must be between {{ min }} and {{ max }}",
     * )
     */
    private $work_live_balance;
    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     * @Assert\Range(
     *  min = 0,
     *  max = 5,
     *  notInRangeMessage = "It must be between {{ min }} and {{ max }}",
     * )
     */
    private $career_development;
    /**
     * @ORM\Column(type="string", length=500)
     */
    private $pro;
    /**
     * @ORM\Column(type="string", length=500)
     */
    private $contra;
    /**
     * @ORM\Column(type="string", length=500)
     */
    private $suggestions;

    /**
     * Get the value of review_id
     */
    public function getReview_id()
    {
        return $this->review_id;
    }

    /**
     * Get the value of title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @return  self
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set the value of user
     *
     * @return  self
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }


    /**
     * Get the value of company
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set the value of company
     *
     * @return  self
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }



    /**
     * Get the value of culture
     */
    public function getCulture()
    {
        return $this->culture;
    }

    /**
     * Set the value of culture
     *
     * @return  self
     */
    public function setCulture($culture)
    {
        $this->culture = $culture;

        return $this;
    }

    /**
     * Get the value of management
     */
    public function getManagement()
    {
        return $this->management;
    }

    /**
     * Set the value of management
     *
     * @return  self
     */
    public function setManagement($management)
    {
        $this->management = $management;

        return $this;
    }

    /**
     * Get the value of work_live_balance
     */
    public function getWorklivebalance()
    {
        return $this->work_live_balance;
    }

    /**
     * Set the value of work_live_balance
     *
     * @return  self
     */
    public function setWorklivebalance($work_live_balance)
    {
        $this->work_live_balance = $work_live_balance;

        return $this;
    }

    /**
     * Get the value of career_development
     */
    public function getCareerdevelopment()
    {
        return $this->career_development;
    }

    /**
     * Set the value of career_development
     *
     * @return  self
     */
    public function setCareerdevelopment($career_development)
    {
        $this->career_development = $career_development;

        return $this;
    }

    /**
     * Get the value of pro
     */
    public function getPro()
    {
        return $this->pro;
    }

    /**
     * Set the value of pro
     *
     * @return  self
     */
    public function setPro($pro)
    {
        $this->pro = $pro;

        return $this;
    }

    /**
     * Get the value of contra
     */
    public function getContra()
    {
        return $this->contra;
    }

    /**
     * Set the value of contra
     *
     * @return  self
     */
    public function setContra($contra)
    {
        $this->contra = $contra;

        return $this;
    }

    /**
     * Get the value of suggestions
     */
    public function getSuggestions()
    {
        return $this->suggestions;
    }

    /**
     * Set the value of suggestions
     *
     * @return  self
     */
    public function setSuggestions($suggestions)
    {
        $this->suggestions = $suggestions;

        return $this;
    }

    public static function getAllReviewsFromCompany($companyId, $reviewRepository)
    {
        return $reviewRepository->findBy(array('company' => $companyId));
    }

    //average rating of a review
    public static function getAvgRating($review)
    {
        return (float)(($review->culture + $review->management + $review->work_live_balance + $review->career_development) / 4);
    }

    //highest and lowest rating review 
    public static function getHighestLowestRatingReview($companyId, $reviewRepository)
    {
        $reviews = Review::getAllReviewsFromCompany($companyId, $reviewRepository);

        if ($reviews) {
            foreach ($reviews as $key => $review) {
                if ($key == 0) {
                    $lowestRating = Review::getAvgRating($reviews[$key]);
                    $highestRating = Review::getAvgRating($reviews[$key]);
                }
                if ($key != 0) {
                    $aux = Review::getAvgRating($review);
                    if ($aux < $lowestRating)
                        $lowestRating = $aux;
                    if ($aux > $highestRating)
                        $highestRating = $aux;
                }
            }
            return array('lowestRating' => $lowestRating, 'highestRating' => $highestRating);
        }
        return null;
    }
}
