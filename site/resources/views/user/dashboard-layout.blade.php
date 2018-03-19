@extends('layouts.app')

@section('content')
<div class="dashboard" ng-controller="DashboardCtrl">
    <div class="container-fluid">
        <div class="row dashboard-summary">
            <div class="col-md-5" ng-show="servers.length === 1">
                Server: @{{selectedServer.name}}
            </div>
            <div class="col-md-5" ng-show="servers.length > 1">
                Servers: <select class="server-select-dropdown"></select>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div ng-controller="ServerCtrl">
                    <div ng-show="showDashboardAll">
                        <dashboard-all></dashboard-all>
                    </div>
                    <div ng-hide="showDashboardAll">
                        <dashboard-server server-data="serverData"></dashboard-server>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
