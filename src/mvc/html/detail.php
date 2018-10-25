<bbn-scroll>
	<div class="k-header bbn-c bbn-spadded">
		<h4 v-text="message" style="margin: 0"></h4>
	</div>
	<div v-for="(item, i) of items">
		<div class="bbn-flex-width">
			<div class="k-header bbn-middle" style="width: 150px">
				<h4><?=_("Field")?></h4>
			</div>
			<div class="bbn-flex-fill k-block bbn-vmiddle">
				<div class="bbn-spadded" v-text="item.column"></div>
			</div>
		</div>
		<div v-if="item.showJSON"  class="bbn-flex-width">
			<div class="k-header bbn-c" style="width: 150px">
				<h4><?=_("JSON")?></h4>
				<br>
				<bbn-switch v-model="item.showJSON"
										:value="true"
										:novalue="false"
										@change="setShowJSON(item)"
				></bbn-switch>
			</div>
			<div class="bbn-flex-fill k-block bbn-vmiddle">
				<bbn-json-editor :value="item.json"
												 :cfg="{mode: 'view', search: false, modes: []}"
				></bbn-json-editor>
			</div>
		</div>
		<div v-else>
			<div v-if="!!item.json" class="bbn-flex-width">
				<div class="k-header bbn-middle" style="width: 150px">
					<h4><?=_("JSON")?></h4>
				</div>
				<div class="bbn-flex-fill k-block bbn-vmiddle">
					<bbn-switch v-model="item.showJSON"
										  :value="true"
										  :novalue="false"
										  @change="setShowJSON(item)"
				></bbn-switch>
				</div>
			</div>
			<div v-if="showBefore" class="bbn-flex-width">
				<div class="k-header bbn-middle" style="width: 150px">
					<h4><?=_("Before")?></h4>
				</div>
				<div class="bbn-flex-fill k-block bbn-vmiddle">
					<div class="bbn-spadded"
							v-text="item.before === null ? 'NULL' : item.before"
							style="word-break: break-all; max-width: 500px"
					></div>
				</div>
			</div>
			<div class="bbn-flex-width">
				<div class="k-header bbn-middle" style="width: 150px">
					<h4><?=_("After")?></h4>
				</div>
				<div class="bbn-flex-fill k-block bbn-vmiddle">
					<div class="bbn-spadded"
							v-text="item.after === null ? 'NULL' : item.after"
							style="word-break: break-all; max-width: 500px"
					></div>
				</div>
			</div>
		</div>
		<br v-if="source.items[i+1] !== undefined">
	</div>
	
</bbn-scroll>
