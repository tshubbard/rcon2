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
	</div>
</template>

<script>
	export default {
		'data': function(){
			return {
				'servers': [],
				'server_selected': null
			}
		},
		'created': function(){
			$.get('/api/v1/user/servers', function(data){
				data.forEach(function(row){
					this.servers.push({'id': row.id, 'name': row.name});
				}.bind(this));
			}.bind(this), 'json');
		},
		'methods': {
			'updateSelectedServer': function(e){
				app.server_selected = e.target.value;
			}
		}
	}
</script>
