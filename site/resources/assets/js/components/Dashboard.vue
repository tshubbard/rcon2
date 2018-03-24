<template>
	<div class="dashboard">
		<div class="container-fluid">
			<div class="row dashboard-summary">
				<div class="col-md-5" v-if="servers.length === 1">
					Server: {{servers[0].name}}
				</div>
				<div class="col-md-5" v-if="servers.length > 1">
					Servers:
						<select class="server-select-dropdown" v-on:change="updateSelectedServer">
							<option v-for="row in servers" :value="row.id">{{row.name}}</option>
						</select>
				</div>
			</div>
		</div>
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="container-fluid">
						<div class="row">
							<div class="dashboard-panel col-md-6">
								<div class="panel-top">
									<div class="md-hue-2">
										<div class="md-toolbar-tools">
											<h2 flex>Server Events</h2>
											<button class="md-icon-button" aria-label="Favorite">
												<i class="fa fa-plus-square fa-fw clickable" aria-hidden="true" v-on:click="showAddEditServerEvent()"></i>
											</button>
										</div>
									</div>
								</div>
								<div class="panel-body">
									<ul class="server-events">
										<li v-for="event in server_selected.events">
											<switch :value="event.is_active" v-on:change="toggleEventActive(event)" aria-label="Server Event Active on/off toggle"></switch>
											<span class="server-event-name">{{event.name}}</span>
											<span class="server-actions float-right">
												<i class="fa fa-pencil fa-fw clickable" v-on:click="showAddEditServerEvent(event)" aria-hidden="true">
												</i>
												<i class="fa fa-trash fa-fw clickable" ng-click="removeServerEvent(event)" aria-hidden="true">
												</i>
											</span>
										</li>
									</ul>
								</div>
							</div>
							<div class="dashboard-panel col-md-6">
								<div class="panel-top">
									<div class="md-hue-2">
										<div class="md-toolbar-tools">
											<h2 flex md-truncate>Active Users</h2>
										</div>
									</div>
								</div>
								<div class="panel-body">
									<p>User List Here</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		'data': function(){
			return {
				'servers': [],
				'server_selected': {
					'events': []
				}
			}
		},
		'created': function() {
		    var serverId = +this.$route.params.serverId;
		    console.log('getting servers');


            $.get('/api/v1/user/servers', function(data) {
                this.servers = data;
                console.log('user/servers data ', data);

                debugger;
				if (serverId) {
				    this.server_selected = _.find(this.servers, function(server) {
				        return server.id === serverId;
					})
				} else {
                    if(this.servers.length > 0) {
                        this.server_selected = this.servers[0];
                    }
				}

                console.log(this.server_selected);
            }.bind(this), 'json');

		},
		'methods': {
			'updateSelectedServer': function(e){
				this.server_selected = this.servers[e.target.value];
			},
			'showAddEditServerEvent': function(event){

			},
			'toggleEventActive': function(event){

			},
			'removeServerEvent': function(event){

			}
		},
		'computed': {
			'eventClass': function(event){
				return 'server-event my-2 ' + (event.is_active ? '' : 'not-') + 'active';
			}
		}
	}
</script>
