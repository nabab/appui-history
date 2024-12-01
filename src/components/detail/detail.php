<!-- HTML Document -->

<div class="appui-history-detail bbn-w-100">
	<div class="bbn-c">
    <h3 v-text="_('UID') + ': ' + source.uid"/>
		<h4 v-if="message"
        v-text="message"/>
	</div>
	<div v-for="(item, i) of items">
		<div class="bbn-flex-width">
			<div class="bbn-header bbn-middle" style="width: 150px">
				<h4><?= _("Field") ?></h4>
			</div>
			<div class="bbn-flex-fill bbn-block bbn-vmiddle">
				<div class="bbn-spadding" v-text="item.column"></div>
			</div>
		</div>
		<div v-if="item.showJSON"  class="bbn-flex-width">
			<div class="bbn-header bbn-c" style="width: 150px">
				<h4>JSON</h4>
				<bbn-switch v-model="item.showJSON"
										:value="true"
										:novalue="false"
										@change="setShowJSON(item)"/>
			</div>
			<div class="bbn-flex-fill bbn-block bbn-vmiddle">
				<bbn-json-editor :value="item.json"
												 :cfg="{mode: 'view', search: false, modes: []}"/>
			</div>
		</div>
		<div v-else>
			<div v-if="!!item.json" class="bbn-flex-width">
				<div class="bbn-header bbn-middle" style="width: 150px">
					<h4>JSON</h4>
				</div>
				<div class="bbn-flex-fill bbn-block bbn-vmiddle">
					<bbn-switch v-model="item.showJSON"
										  :value="true"
										  :novalue="false"
										  @change="setShowJSON(item)"/>
				</div>
			</div>
			<div v-if="showBefore" class="bbn-flex-width">
				<div class="bbn-header bbn-middle" style="width: 150px">
					<h4><?= _("Before") ?></h4>
				</div>
				<div class="bbn-flex-fill bbn-block bbn-vmiddle">
					<div class="bbn-spadding"
							v-text="item.before === null ? 'NULL' : item.before"
							style="word-break: break-all; max-width: 500px"
					></div>
				</div>
			</div>
			<div class="bbn-flex-width">
				<div class="bbn-header bbn-middle" style="width: 150px">
					<h4><?= _("After") ?></h4>
				</div>
				<div class="bbn-flex-fill bbn-block bbn-vmiddle">
					<div class="bbn-spadding"
							v-text="item.after === null ? 'NULL' : item.after"
							style="word-break: break-all; max-width: 500px"
					></div>
				</div>
			</div>
		</div>
		<br v-if="source.items[i+1] !== undefined">
	</div>
</div>
