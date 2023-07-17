@extends('errors::minimal')

@section('title', 'Cấm')
@section('code', '403')
@section('message', __($exception->getMessage() ?: 'Cấm'))
