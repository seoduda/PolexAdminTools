<?php
/**
 * Created by PhpStorm.
 * User: Duda
 * Date: 11/03/2016
 * Time: 00:39
 */

class SubscriptionReportData {
    public $date , $revista_site_Subscriptions, $site_Subscriptions ;

    function __construct($date)
    {
        $this->date = $date;
        $this->revista_site_Subscriptions = 0;
        $this->site_Subscriptions = 0;
    }

    /**
     * @return mixed
     */
    public function getRevistaSiteSubscriptions()
    {
        return $this->revista_site_Subscriptions;
    }

    /**
     * @param mixed $revista_site_Subscriptions
     */
    public function setRevistaSiteSubscriptions($revista_site_Subscriptions)
    {
        $this->revista_site_Subscriptions = $revista_site_Subscriptions;
    }

    /**
     * @return mixed
     */
    public function getSiteSubscriptions()
    {
        return $this->site_Subscriptions;
    }

    /**
     * @param mixed $site_Subscriptions
     */
    public function setSiteSubscriptions($site_Subscriptions)
    {
        $this->site_Subscriptions = $site_Subscriptions;
    }

    function toString()
    {
        $s = 'date:'.$this->date.' revista_site_Subscriptions: '.$this->revista_site_Subscriptions. ' site_Subscriptions: '.$this->site_Subscriptions;
        return $s;
    }


} 