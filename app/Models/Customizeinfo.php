<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customizeinfo extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'customizeinfo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'first_section_text',
        'first_section_text_fr',
        'second_section_text',
        'second_section_text_fr',
        'phone_number',
        'email',
        'skype',
        'website_link',
        'facebook_link',
        'twitter_link',
        'pinterest_link',
        'instagram_link',
        'link_name',
        'link',
        'property',
        'city',
        'photo',
        'slides_per_row',
        'autoplay_speed',
        'navigation_type',
        'block_name',
        'user',
        'link_type',
        'title',
        'description',
        'keywords',
        'header_back_type',
        'header_title',
        'header_subtitle',
        'header_back_video_link',
        'website_title',
        'block_subname',
        'header_back_audio_status',
        'video',
        'header_title_fr',
        'header_subtitle_fr',
        'block_name_fr',
        'block_subname_fr'
    ];

    public function propertyInfo() {
        return Properties::find($this->property);
    }
}
