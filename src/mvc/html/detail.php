<bbn-scroll>
	<div class="bbn-flex-width">
		<div class="k-header bbn-middle" style="width: 150px">
			<h4><?=_("Champ")?></h4>
		</div>
		<div class="bbn-flex-fill k-block bbn-vmiddle">
			<div class="bbn-spadded" v-text="source.column"></div>
		</div>
	</div>
	<div v-if="showBefore" class="bbn-flex-width">
		<div class="k-header bbn-middle" style="width: 150px">
			<h4><?=_("Avant")?></h4>
		</div>
		<div class="bbn-flex-fill k-block bbn-vmiddle">
			<div class="bbn-spadded"
					 v-text="beforeText"
					 style="word-break: break-all; max-width: 500px"
		  ></div>
		</div>
	</div>
	<div class="bbn-flex-width">
		<div class="k-header bbn-middle" style="width: 150px">
			<h4><?=_("Après")?></h4>
		</div>
		<div class="bbn-flex-fill k-block bbn-vmiddle">
			<div class="bbn-spadded"
					 v-text="afterText"
					 style="word-break: break-all; max-width: 500px"
		  ></div>
		</div>
	</div>
	<div class="k-header bbn-c bbn-spadded">
		<h4 v-text="message" style="margin: 0"></h4>
	</div>
</bbn-scroll>
