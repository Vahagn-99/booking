<form action="{{ route('save-customizes') }}" method="post">
    @csrf
    <input type="hidden" name="hash" value="">
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#en">{!! __('messages.En') !!}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#fr">{!! __('messages.Fr') !!}</a>
        </li>
    </ul>
    <div class="tab-content pt-3">
        <div id="en" class="tab-pane active">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="first_section_text">{!! __('messages.First section text') !!}</label>
                        <textarea name="website-footer&first_section_text" class="form-control" rows="10" id="first_section_text">{!! old('website-footer&first_section_text') ? old('website-footer&first_section_text') : ($wesbiteFooter ? str_replace('<br>', "\n", $wesbiteFooter->first_section_text) : '') !!}</textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="second_section_text">{!! __('messages.Second section text') !!}</label>
                        <textarea name="website-footer&second_section_text" class="form-control" rows="10" id="second_section_text">{!! old('website-footer&second_section_text') ? old('website-footer&second_section_text') : ($wesbiteFooter ? str_replace('<br>', "\n", $wesbiteFooter->second_section_text) : '') !!}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div id="fr" class="tab-pane fade">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="first_section_text_fr">{!! __('messages.First section text') !!}</label>
                        <textarea name="website-footer&first_section_text_fr" class="form-control" rows="10" id="first_section_text_fr">{!! old('website-footer&first_section_text_fr') ? old('website-footer&first_section_text_fr') : ($wesbiteFooter ? str_replace('<br>', "\n", $wesbiteFooter->first_section_text_fr) : '') !!}</textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="second_section_text_fr">{!! __('messages.Second section text') !!}</label>
                        <textarea name="website-footer&second_section_text_fr" class="form-control" rows="10" id="second_section_text_fr">{!! old('website-footer&second_section_text_fr') ? old('website-footer&second_section_text_fr') : ($wesbiteFooter ? str_replace('<br>', "\n", $wesbiteFooter->second_section_text_fr) : '') !!}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label for="phone_number">{!! __('messages.Phone number') !!}</label>
                <input type="text" class="form-control" id="phone_number" name="website-footer&phone_number" value="{!! old('website-footer&phone_number') ? old('website-footer&phone_number') : ($wesbiteFooter ? $wesbiteFooter->phone_number : '') !!}">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="email">{!! __('messages.Email') !!}</label>
                <input type="text" class="form-control" id="email" name="website-footer&email" value="{!! old('website-footer&email') ? old('website-footer&email') : ($wesbiteFooter ? $wesbiteFooter->email : '') !!}">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="skype">{!! __('messages.Skype') !!}</label>
                <input type="text" class="form-control" id="skype" name="website-footer&skype" value="{!! old('website-footer&skype') ? old('website-footer&skype') : ($wesbiteFooter ? $wesbiteFooter->skype : '') !!}">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="website_link">{!! __('messages.Website link') !!}</label>
                <input type="text" class="form-control" id="website_link" name="website-footer&website_link" value="{!! old('website-footer&website_link') ? old('website-footer&website_link') : ($wesbiteFooter ? $wesbiteFooter->website_link : '') !!}">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="facebook_link">{!! __('messages.Facebook link') !!}</label>
                <input type="text" class="form-control" id="facebook_link" name="website-footer&facebook_link" value="{!! old('website-footer&facebook_link') ? old('website-footer&facebook_link') : ($wesbiteFooter ? $wesbiteFooter->facebook_link : '') !!}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="twitter_link">{!! __('messages.Twitter link') !!}</label>
                <input type="text" class="form-control" id="twitter_link" name="website-footer&twitter_link" value="{!! old('website-footer&twitter_link') ? old('website-footer&twitter_link') : ($wesbiteFooter ? $wesbiteFooter->twitter_link : '') !!}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="pinterest_link">{!! __('messages.Pinterest link') !!}</label>
                <input type="text" class="form-control" id="pinterest_link" name="website-footer&pinterest_link" value="{!! old('website-footer&pinterest_link') ? old('website-footer&pinterest_link') : ($wesbiteFooter ? $wesbiteFooter->pinterest_link : '') !!}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="instagram_link">{!! __('messages.Instagram link') !!}</label>
                <input type="text" class="form-control" id="instagram_link" name="website-footer&instagram_link" value="{!! old('website-footer&instagram_link') ? old('website-footer&instagram_link') : ($wesbiteFooter ? $wesbiteFooter->instagram_link : '') !!}">
            </div>
        </div>
    </div>
    <div class="form-group mt-4">
        <h4>{!! __('messages.Section "More information"') !!}</h4>
    </div>
    <div class="form-group">
        @for ($i = 0; $i < count($websiteFooterMoreInfo); $i++)
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="link_name_{{ $i }}">{!! __('messages.Link title') !!}</label>
                        <input type="text" name="website-footer-more-info&link_name&{{ $websiteFooterMoreInfo[$i]->id }}" value="{!! $websiteFooterMoreInfo[$i]->link_name !!}" class="form-control" id="link_name_{{ $i }}">
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="form-group">
                        <label for="link_{{ $i }}">{!! __('messages.Link URL') !!}</label>
                        <input type="text" name="website-footer-more-info&link&{{ $websiteFooterMoreInfo[$i]->id }}" value="{!! $websiteFooterMoreInfo[$i]->link !!}" class="form-control" id="link_{{ $i }}">
                    </div>
                </div>
                <div class="col-md-1 d-flex items-end">
                    <div class="form-group">
                        <button class="btn btn-danger admin-delete-el w-100" data-id="{{ $websiteFooterMoreInfo[$i]->id }}" type="button"><i class="fas fa-times"></i></button>
                    </div>
                </div>
            </div>
        @endfor
        <div class="text-left">
            <button type="button" class="btn btn-sm btn-primary mt-2 admin-add-el" data-name="website-footer-more-info">{!! __('messages.Add link') !!}</button>
        </div>
    </div>

    <div class="text-right">
        <button type="submit" class="btn btn-success">{!! __('messages.Save') !!}</button>
    </div>
</form>
