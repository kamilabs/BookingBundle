KamiBookingBundle
=================

[![Build Status](https://travis-ci.org/kamilabs/BookingBundle.svg?branch=master)](https://travis-ci.org/kamilabs/BookingBundle)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/5f951963-0bf5-4721-b2ef-7822800f740f/mini.png)](https://insight.sensiolabs.com/projects/5f951963-0bf5-4721-b2ef-7822800f740f)
[![HHVM Status](http://hhvm.h4cc.de/badge/kami/booking-bundle.svg)](http://hhvm.h4cc.de/package/kami/booking-bundle)

-------------



Booking Bundle for Symfony 2 Applications. Bundle provides some useful functionality for handling bookings
on your website.


Installation
-------------

### 1. Download
Prefered way to install this bundle is using [composer](http://getcomposer.org)

Download the bundle:
```bash
$ php composer.phar require "kami/booking-bundle"
```
### 2. Add it to your Kernel:

```php
<?php

// app/AppKernel.php


public function registerBundles()
{
    $bundles = array(
        // ...

        new Kami\BookingBundle\KamiBookingBundle(),
    );
}
```
### 3. Create your entity

#### Doctrine ORM
Bundle has all necessary mappings for your entity. Just create your entity class and extend it from
```Kami\BookingBundle\Entity\Booking```, create your ```id``` field and setup proper relation for
item you want to be booked.

```php
<?php

namespace Vendor\Bundle\Entity;

use Kami\BookingBundle\Entity\Booking as BaseClass;

/**
 * Booking
 *
 * @ORM\Entity()
 * @ORM\Table(name="booking")
 */
class Booking extends BaseClass
{
    /**
         * @var integer
         *
         * @ORM\Column(name="id", type="integer")
         * @ORM\Id
         * @ORM\GeneratedValue(strategy="AUTO")
         */
        protected $id;

        /**
         * @var \Vendor\Bundle\Entity\BookableItem
         *
         * @ORM\ManyToOne(targetEntity="BookableItem", inversedBy="bookings")
         * @ORM\JoinColumn(name="property_id", referencedColumnName="id")
         */
        protected $item;

        // Don't forget about getters and setters
}

```

Now we are ready to rock!

Booker Service
--------------

Core component of this bundle is booker service. You can get it in your controller by using
```php
<?php

public function bookingAction()
{
    $this->get('booker'); /** @var \Kami\BookingBundle\Helper\Booker */
}
```

#### Booker Service has following methods:

``` isAvailableForPeriod($item, \DateTime $start, \DateTime $end) ``` Checks is your item available for period,
returns ```boolean```

---

``` isAvailableForDate($item, \DateTime $date) ``` Checks is your item available for date, returns ```boolean```

---

``` whereAvailableForPeriod(QueryBuilder $queryBuilder, $join, \DateTime $start, \DateTime $end)``` Updates your
```QueryBuilder``` and returns the same ```QueryBuilder``` object with added join and where clause.
> Note: ```$join``` is ```array('field', 'alias')```

---

``` whereAvailableForDate(QueryBuilder $queryBuilder, $join, \DateTime $date)``` Updates your
```QueryBuilder``` and returns the same ```QueryBuilder``` object with added join and where clause.
> Note: ```$join``` is ```array('field', 'alias')```

---

``` book($item, \DateTime $start, \DateTime $end) ``` Books your item returns ```Entity | false``` (```Entity```
on success, ```false``` on failure)

Calendar Twig Extension
-----------------------

Bundle also provides cool Twig extension. To use it in your template just try following:

```twig
{{ kami_booking_calendar(item, "now", 4) }}
```

Where

```item``` - is object of your bookable item

```now```  -  is any date allowed for ```\DateTime::__construct()```

```4```    -  number of months to be rendered after desired date

### Overriding template

Template can be overridden as usual in Symfony application.
Just create following directory structure:

```
app/Resources/views/KamiBookingBundle/Calendar/month.html.twig
```
