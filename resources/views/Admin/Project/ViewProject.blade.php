@extends('Admin.Layouts.Master')

@section('Content')
    <div>
        @php
            use App\Models\Project;
            use Illuminate\Support\Facades\DB;
            $project = Project::find(10);
            $properties = DB::table('properties')->where('id', '<', 16)->orderBy('id', 'asc')->get(['id','name']);
        @endphp
        <x-home.project.content-project :properties="$properties" :project="$project"></x-home.project.content-project>
    </div>
@endsection
