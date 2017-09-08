{{-- modal --}}
<div class="modal fade" id="condemnWorkstationModal" tabindex="-1" role="dialog" aria-labelledby="condemnWorkstationModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<legend><h3 class="text-muted">Condemn Workstation</h3></legend>
					<p class="text-muted">Check the parts of workstation you want to condemn. <span class="text-danger">Clicking condemn will permanently remove the connection of each part as workstation</span></p>
					<ul class="list-group">
					  	<li class="list-group-item">
						  	<input type="checkbox" name="systemunit" id="condemn-systemunit" value="on" checked /> System Unit:  
						  	<span id="condemn-systemunit-text"></span>
			  			</li>
					  	<li class="list-group-item">
						  	<input type="checkbox" name="monitor" id="condemn-monitor" value="on" /> Monitor:  
						  	<span id="condemn-monitor-text"></span>
			  			</li>
					  	<li class="list-group-item">
						  	<input type="checkbox" name="avr" id="condemn-avr" value="on" /> AVR:  
						  	<span id="condemn-avr-text"></span>
			  			</li>
					  	<li class="list-group-item">
						  	<input type="checkbox" name="keyboard" id="condemn-keyboard" value="on"/> Keyboard:  
						  	<span id="condemn-keyboard-text"></span>
			  			</li>
					</ul>		
				<div class="form-group">
				<button class="btn btn-block btn-lg btn-danger" data-loading-text="Loading..." type="button" id="condemn-button">Condemn</button>
				</div>
			</div> <!-- end of modal-body -->
		</div> <!-- end of modal-content -->
	</div>
</div>