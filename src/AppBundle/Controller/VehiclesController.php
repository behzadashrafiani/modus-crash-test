<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use Unirest;

class VehiclesController extends AbstractController
{

    /**
     * @Route("/vehicles/{year}/{manufacturer}/{model}")
     * @Method({"get"})
     */
    public function getVehicles($year, $manufacturer, $model, Request $request)
    {
        $withRating = $request->query->get('withRating');
        $headers = array('Accept' => 'application/json');
        $response = Unirest\Request::get("https://one.nhtsa.gov/webapi/api/SafetyRatings/modelyear/$year/make/$manufacturer/model/$model?format=json", $headers);
        $data = json_decode($response->raw_body, true);
        if($data["Count"]==0) {
            return [
                "Count" => 0,
                "Results" => []
            ];
        }
        foreach ($data["Results"] as $datum) {
            if ($withRating==="true") {
                $ratingResponse = Unirest\Request::get("https://one.nhtsa.gov/webapi/api/SafetyRatings/VehicleId/" . $datum["VehicleId"] . "?format=json", $headers);
                $ratingData = json_decode($ratingResponse->raw_body, true);
                $resultArray[] = [
                    "CrashRating" => $ratingData["Results"][0]["OverallRating"],
                    "Description" => $datum["VehicleDescription"],
                    "VehicleId" => $datum["VehicleId"]
                ];
            } else
                $resultArray[] = [
                    "Description" => $datum["VehicleDescription"],
                    "VehicleId" => $datum["VehicleId"]
                ];


        }
        return [
            "Count" => $data["Count"],
            "Results" => $resultArray
        ];
    }

    /**
     * @Route("/vehicles")
     * @Method({"post"})
     */
    public function postVehicles(Request $request)
    {
        $params = $request->request->all();
        $headers = array('Accept' => 'application/json');
        $response = Unirest\Request::get("https://one.nhtsa.gov/webapi/api/SafetyRatings/modelyear/" . $params["modelYear"] .
            "/make/" . $params["manufacturer"] . "/model/" . $params["model"] . "?format=json", $headers);
        $data = json_decode($response->raw_body, true);
        if($data["Count"]==0)
            return [
                "Count" => 0,
                "Results" => []
            ];
        foreach ($data["Results"] as $datum) {
            if (isset($params["withRating"]) && $params["withRating"] === "true") {
                $ratingResponse = Unirest\Request::get("https://one.nhtsa.gov/webapi/api/SafetyRatings/VehicleId/" . $datum["VehicleId"] . "?format=json", $headers);
                $ratingData = json_decode($ratingResponse->raw_body, true);
                $resultArray[] = [
                    "CrashRating" => $ratingData["Results"][0]["OverallRating"],
                    "Description" => $datum["VehicleDescription"],
                    "VehicleId" => $datum["VehicleId"]
                ];
            } else
                $resultArray[] = [
                    "Description" => $datum["VehicleDescription"],
                    "VehicleId" => $datum["VehicleId"]
                ];
        }
        return [
            "Count" => $data["Count"],
            "Results" => $resultArray
        ];
    }

}