<div class="bbn-lpadding bbn-grid-fields">
  <bbn-input placeholder="<?= _('Type an uid') ?>"
             v-model="uid"
             @keydown.enter="searchUid"
  ></bbn-input>
  <div>
  	<bbn-button icon="nf nf-fa-search"
              class="bbn-small"
              :notext="true"
              @click="searchUid"
              title="<?= _("Search uid") ?>"
              style="margin-top:2px"
  ></bbn-button>  
  </div>
</div>
<div class="bbn-vspadding bbn-hlpadding bbn-large bbn-grid-fields"
>
  <div v-if="table.length">
    <?= _('Table') ?>:
  </div>
  <div v-text="table ? table : ''">
  </div>
</div>
<div class="bbn-vspadding bbn-hlpadding bbn-large bbn-grid-fields"
>
  <div v-if="table"><?= _('Active') ?>:
  </div>
  <div v-if="table">
  	<div v-text="active" style="display:inline">
  	</div>
    <bbn-button icon="nf nf-fa-trash_alt"
                class="bbn-small"
                label="<?= ("Delete completely from db?") ?>"
                @click="removeAll"
                title="<?= ("Delete completely from db?") ?>"
                style="margin-left:1rem"
   	></bbn-button>
  </div>
  
</div>
<div class="bbn-vspadding bbn-hlpadding bbn-grid-fields">
  <div v-if="opr.length" 
       class="bbn-large"
       :title="(opr.length === 1) ? opr.length +' operation' : opr.length +' operations'"
  >
     <?= _('Operation(s)') ?>:
 	</div>
  <div style="position:relative; height:650px" class="list-container">
    <bbn-scroll>
      <ul>
        <li v-for="(o, idx) in opr" >
          <div v-if="opr[idx].opr" 
             v-text="opr[idx].opr ? opr[idx].opr : '-'" 
             :class="[{
                     'bbn-red': (opr[idx].opr === 'DELETE'),
                     'bbn-green': (opr[idx].opr === 'INSERT'),
                     'bbn-orange': (opr[idx].opr === 'UPDATE'),      
                     }]"
          ></div>   
          <div v-text="opr[idx].col ? opr[idx].col : '-'" ></div>
          <div v-text="opr[idx].usr ? username(opr[idx].usr) : '-'"></div>
          <div v-text="opr[idx].dt ? fdate(opr[idx].dt) : '-'" ></div>
          <div>
            <i class="nf nf-fa-times bbn-p" @click="deleteRow(o, idx)"></i>
          </div>
          <div class="bbn-widget" style="grid-column: 1/6!important;width:100%!important;">
          </div>
        </li>		   
      </ul>
    </bbn-scroll>
  </div>
</div>