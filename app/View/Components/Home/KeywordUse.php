<?php

namespace App\View\Components\Home;

use App\Models\District;
use App\Models\FeaturedKeyword;
use App\Models\Group;
use App\Services\GroupService;
use Illuminate\View\Component;

class KeywordUse extends Component
{
    public $featuredKeywords;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($group = null, $province = null)
    {
        if ($group || $province) {
            if ($province) {
                $buyGroup = (new GroupService())->getGroupFromUrl('nha-dat-ban');
                $sellGroup = (new GroupService())->getGroupFromUrl('nha-dat-cho-thue');

                $childrenIds = $buyGroup->getChildrenGroupIds();
                $childrenIds = array_unique(array_merge($childrenIds, $sellGroup->getChildrenGroupIds()));
            } else {
                $childrenIds = $group->getChildrenGroupIds();
            }

            $featuredKeywords = [];
            $paradigmFeaturedCount = FeaturedKeyword::where('target_type', Group::class)
                ->whereIn('target_id', $childrenIds)
                ->active()
                ->count();
            $districtFeaturedCount = FeaturedKeyword::where('target_type', District::class)
                ->active()
                ->count();

            // label = $paradigmLabel . ' ' . $districtLabel nên max label = paradigm * district
            $maxFeaturedSearched = $paradigmFeaturedCount * $districtFeaturedCount;
            $maxKeyWord = config('constants.classified.search.featured_keyword.max_keyword_special', 8);
            $limitFeaturedKeywords = min($maxFeaturedSearched, $maxKeyWord);

            for ($i = 0; $i < $limitFeaturedKeywords; $i++) {
                // lấy theo thứ tự từ nhiều nhất đến ít nhất từng lượt. nếu hết thì quay về lại cái nhiều nhất theo thứ tự
                $paradigmOffset = $i >= $paradigmFeaturedCount ? $i - $paradigmFeaturedCount : $i;

                if ($paradigmOffset >= $paradigmFeaturedCount) {
                    $paradigmOffset = 0;
                }

                $paradigmFeatured = FeaturedKeyword::where('target_type', Group::class)
                    ->whereIn('target_id', $childrenIds)
                    ->active()
                    ->latest('views')
                    ->offset($paradigmOffset)
                    ->first();

                $districtOffset = $i >= $districtFeaturedCount ? $i - $districtFeaturedCount : $i;
                if ($districtOffset >= $districtFeaturedCount) {
                    $districtOffset = 0;
                }
                $districtFeatured = FeaturedKeyword::select('featured_keywords.*')
                    ->where('target_type', District::class)
                    ->when($province, function ($query, $province) {
                        $query->leftJoin('district', 'district.id', 'featured_keywords.target_id')
                            // quận huyện của vị trí tỉnh hiện tại
                            // ưu tiên
                            ->orderByRaw("district.province_id = $province->id DESC");
                            // chỉ lấy
                            // ->where('district.province_id', $province->id);
                    })
                    ->active()
                    ->latest('views')
                    ->offset($districtOffset)
                    ->first();

                if (!$paradigmFeatured || !$districtFeatured) break;

                $featuredParadigm = $paradigmFeatured->featuredable;
                $parent = $featuredParadigm->parent ?: $featuredParadigm;
                switch($parent->group_url) {
                    case 'nha-dat-ban':
                        $prefix = 'Bán ';
                        break;
                    case 'du-an':
                        $prefix = 'Dự án ';
                        break;
                    default:
                        $prefix = '';
                        break;
                }

                $paradigmLabel = $featuredParadigm->getNameLabel();
                $paradigmLabel = str_replace(', ', ' - ', $paradigmLabel);
                $districtLabel = $districtFeatured->featuredable->getNameLabel();
                $districtLabel = str_replace(', ', ' - ', $districtLabel);

                $link = route('home.classified.list', [$featuredParadigm->getLastParentGroup(), $featuredParadigm->parent_id ? $featuredParadigm->group_url : null]);
                $link .= "?province_id={$districtFeatured->featuredable->province_id}&district_id={$districtFeatured->target_id}";

                $featuredKeywords[] = [
                    'link' => $link,
                    'label' => $prefix . $paradigmLabel . ' ' . $districtLabel
                ];
            }

            $this->featuredKeywords = $featuredKeywords;
        } else {
            $this->featuredKeywords = [];
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.home.keyword-use');
    }
}
