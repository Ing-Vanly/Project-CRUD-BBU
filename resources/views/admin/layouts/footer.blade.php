<footer class="main-footer">
    @php($displayName = $appBusinessSetting->name ?? 'AdminLTE.io')
    <strong>&copy; {{ now()->year }} {{ $displayName }}.</strong>
    <span>
        {{ $appBusinessSetting && $appBusinessSetting->footer_text ? $appBusinessSetting->footer_text : __('All rights reserved.') }}
    </span>
    <div class="float-right d-none d-sm-inline-block">
        <b>{{ __('Timezone') }}</b>
        {{ $appBusinessSetting && $appBusinessSetting->timezone ? $appBusinessSetting->timezone : config('app.timezone') }}
    </div>
</footer>
