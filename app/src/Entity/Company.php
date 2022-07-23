<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="company")
 */
class Company
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $company_id;
    /**
     * @ORM\Column(type="string", length=200)
     * @Assert\NotBlank()
     *
     */
    private $name;
    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     */
    private $city;
    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     */
    private $country;
    /**
     * @ORM\Column(type="string", length=200)
     * @Assert\NotBlank()
     */
    private $industry;
    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank()
     */
    private $avg_rating;

    /**
     * Get the value of company_id
     */
    public function getId()
    {
        return $this->company_id;
    }

    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of city
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set the value of city
     *
     * @return  self
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get the value of country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set the value of country
     *
     * @return  self
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get the value of industry
     */
    public function getIndustry()
    {
        return $this->industry;
    }

    /**
     * Set the value of industry
     *
     * @return  self
     */
    public function setIndustry($industry)
    {
        $this->industry = $industry;

        return $this;
    }

    /**
     * Get the value of avg_rating
     */
    public function getAvgrating()
    {
        return $this->avg_rating;
    }

    //method to get top 10 companies calculated based on the AVG of all rating  dimensions
    public static function getTopCompanies($companies)
    {
        //avg_rating order by desc
        if ($companies) {
            $avg_rating = array_column($companies, 'avg_rating');
            array_multisort($avg_rating, SORT_DESC, $companies); //order by average rating sort desc
            //top 10
            $companies = array_slice($companies, 0, 10); //get top 10
        }
        return $companies;
    }

    //get avg rating from company
    public function avgRatingCompany($reviewRepository)
    {
        $avg = 0;
        //numero de reviews
        $numbReviews = 0;
        $reviews = Review::getAllReviewsFromCompany($this->company_id, $reviewRepository);

        foreach ($reviews as $review) {
            $numbReviews++;
            $avg += Review::getAvgRating($review);
        }
        $finalRating = $avg / $numbReviews;
        return number_format($finalRating, 2, '.', '');
    }

    //update company avg_rating (afterSave runs after saving a review)
    public function updateAvgRating($reviewRepository)
    {
        //update the company's average rating after a review of this same company is submitted
        $this->avg_rating = $this->avgRatingCompany($reviewRepository);
    }
}
