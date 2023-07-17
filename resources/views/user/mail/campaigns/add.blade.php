@extends('user.layouts.master')

@section('title', 'Tạo chiến dịch')

@section('content')
  <x-user.breadcrumb active-label="Tạo chiến dịch" />

  <div class="container-fluid">
    <div class="row">
      <div class="col-md-7-3 mb-0 mb-md-4">
        <form action="{{ route('user.campaigns.store') }}" method="POST">
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
              <i class="fas fa-plus-circle"></i>&nbsp;Tạo chiến dịch
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
