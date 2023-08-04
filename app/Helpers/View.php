<?php

use Illuminate\Support\Facades\Auth;

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

    for ($i = 1; $i <= 5; $i++) {
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

    for ($i = 0; $i < $stars; $i++) {
        $result .= '<span class="yellow-stars-box__icon"></span>';
    }

    $result .= '</div>';

    return $result;
}

/**
 * render news video description
 * @return string
 */
function renderLightboxDescription($new)
{
    $user = Auth::guard('user')->user();
    $time = date('d/m/Y', $new->created_at);

    $likeActionClass = $user ? 'js-focus__toggle-reaction' : '';
    $likeBoxClass = $new->isLiked(data_get($user, 'id')) ? 'active' : '';
    $dislikeBoxClass = $new->isDisliked(data_get($user, 'id')) ? 'active' : '';

    $shareUrl = data_get($new->group, 'group_url') ? route('home.focus.detail', [data_get($new->group, 'group_url'), $new->news_url]) : '';

    $des = '<h3 class="title-box">Tiêu đề: ' . $new->news_title . '</h3>
        <span class="box-meta">
            <span class="box-view">' . $new->num_view . ' lượt xem </span>
            <span class="box-time">• ' . $time . '</span>
        </span>
        <div class="box-function">
            <div class="text-gray mr-3 flex-start">
                <i class="fas fa-signal mr-2 fs-normal"></i>
                <span class="text-cyan" title="lượt xem">'. $new->num_view .'</span>
            </div>
            <div class="js-focus__like-action flex-start">
                <span class="' . $likeActionClass . '" data-id="' . $new->id . '" data-type="1">
                    <span class="js-reaction-icon mr-2 ' . $likeBoxClass . '">
                        <i class="fas fa-thumbs-up"></i>
                    </span>
                    <span class="js-num-likes" title="lượt thích">'
                        . $new->num_like .
                    '</span>
                </span>
                <span class="' . $likeActionClass . '" data-id="' . $new->id . '" data-type="0">
                    <span class="js-reaction-icon mr-2 ' . $dislikeBoxClass . '">
                        <i class="fas fa-thumbs-down"></i>
                    </span>
                    <span class="js-num-dislikes" title="lượt không thích">'
                        . $new->num_dislike .
                    '</span>
                </span>
            </div>
            <span class="box-share"><a href="https://www.facebook.com/sharer/sharer.php?u=' . $shareUrl . '" target="_blank"><i class="fas fa-share"></i> chia sẻ</a></span>
        </div>
        <div class="box-desc">' . $new->news_content . '</div>
        <div class="box-author">
            <span>' . data_get($new->admin, 'admin_fullname') . '</span>
        </div>';

    // <span class="posts-more dropdown">
    //     <i class="fas fa-ellipsis-h" data-toggle="dropdown" aria-expanded="false"></i>
    //     <div class="dropdown-menu dropdown-menu-right">
    //         <a href="#" class="dropdown-item">
    //             Báo cáo
    //         </a>
    //     </div>
    // </span>

    return $des;
}
