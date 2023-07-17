<div class="social-network-info-account">
    <!-- <div class="title-update-social-link title-update-account"> -->
    <div class="title-update-account">
        <h5>Mạng xã hội</h5>
    </div>
    <form action="{{route('user.post-update-social-link')}}" method="post">
        @csrf
        <label>Link Facebook</label>
        <input type="text" class="form-control" name="facebook" value="{{ old('facebook', data_get($user_social_link, 'facebook')) }}">
        @php show_validate_error($errors, "facebook"); @endphp
        <label>Link Youtube</label>
        <input type="text" class="form-control" name="youtube" value="{{ old('youtube', data_get($user_social_link, 'youtube')) }}">
        @php show_validate_error($errors, "youtube"); @endphp
        <label>Link Twitter</label>
        <input type="text" class="form-control" name="twitter" value="{{ old('twitter', data_get($user_social_link, 'twitter')) }}">
        @php show_validate_error($errors, "twitter"); @endphp
        <div class="click-update"><input type="submit" class="btn update-info-btn" value="Lưu"></div>
    </form>
</div>
