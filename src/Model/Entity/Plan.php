<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property string $title
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property \Cake\I18n\FrozenTime|null $created
 * @property int $id
 * @property int $enable
 * @property int $hidden
 * @property string|null $description
 * @property float $monthly_price
 * @property float $yearly_price
 * @property int $edit_link
 * @property int $edit_long_url
 * @property int $multi_domains
 * @property int $disable_ads
 * @property int $disable_captcha
 * @property int $onetime_captcha
 * @property int $direct
 * @property int $alias
 * @property int $referral
 * @property int $stats
 * @property int $api_quick
 * @property int $api_mass
 * @property int $api_full
 * @property int $api_developer
 * @property int $bookmarklet
 * @property \App\Model\Entity\User[] $users
 * @property \Cake\ORM\Entity|null $title_translation
 * @property \Cake\ORM\Entity|null $description_translation
 * @property \Cake\ORM\Entity[] $_i18n
 * @property int $visitors_remove_captcha
 * @property float $cpm_fixed
 * @property int $link_expiration
 * @property int $url_daily_limit
 * @property int $url_monthly_limit
 * @property int $referral_percentage
 * @property int $views_hourly_limit
 * @property int $views_daily_limit
 * @property int $views_monthly_limit
 * @property int $timer
 * @property int $direct_redirect
 * @property int $banner_redirect
 * @property int $interstitial_redirect
 * @property int $random_redirect
 */
class Plan extends Entity
{
}
