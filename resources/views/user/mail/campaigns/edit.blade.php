@extends('user.layouts.master')

@section('title', 'Chỉnh sửa chiến dịch')

@section('content')
  <x-user.breadcrumb
    active-label="Chỉnh sửa chiến dịch"
    :parents="[
      [
        'label' => 'Thành viên',
        'route' => 'user.index'
      ],
      [
        'label' => 'Quản lý chiến dịch',
        'route' => 'user.campaigns.index'
      ],
    ]"
  />

  <div class="container-fluid">
    <div class="row">
      <div class="col-md-7-3 mb-0 mb-md-4">
        <form action="{{ route('user.campaigns.update', $campaign) }}" method="POST">
          @csrf

          @include('user.mail.campaigns.partials._form', [
              'campaign' => $campaign,
              'provinces' => $provinces,
              'sources' => $sources,
              'statuses' => $statuses,
              'jobs' => $jobs,
              'mail_templates' => $mail_templates,
              'customers' => $customers
          ])

          <div class="text-right">
            <button type="submit" class="btn btn-light-cyan">
              <i class="fas fa-save mr-1"></i>&nbsp;Lưu
            </button>
          </div>
        </form>
      </div>

      <div class="col-md-3-7 mb-4">
        <x-user.guide :guide="$guide" />
      </div>
    </div>
  </div>
@endsection
