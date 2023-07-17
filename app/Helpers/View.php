<?php

/**
 * Show rating in stars icon
 * @param int $rating
 * @param boolean $isShowSelected = false
 * 
 * @return string
 */
function renderRating($rating, $isShowSelected = false)
{
    $rating = (int) $rating;


    $result = '<div class="box-rating"/>';

    for($i = 1; $i <= 5; $i++) {
        $selectedClass = $isShowSelected && $i == $rating ? ' selected' : '';
        $result .= $rating < $i ? '<i class="far fa-star' . $selectedClass . '"></i>' : '<i class="fas fa-star' . $selectedClass . '"></i>';
    }

    $result .= '</div>';

    return $result;
}

/**
 * Show stars in yellow star icon svg
 * @param int $stars
 * 
 * @return string
 */
function renderYellowStars($stars) 
{
    $stars = (int) $stars;

    $result = '<div class="yellow-stars-box__list"/>';

    for($i = 0; $i < $stars; $i++) {
        $result .= '<span class="yellow-stars-box__icon"></span>';
    }

    $result .= '</div>';

    return $result;
}
