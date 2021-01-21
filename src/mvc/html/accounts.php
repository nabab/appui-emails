<!-- HTML Document -->
<bbn-table :sortable="true"
           editable="popup"
           url="emails/accounts/update"
           :data="source"
           :editor="$options.components.accountForm"
           :source="source.data"
           :toolbar="[{text: _('New account'), action: 'insert'}]">
  <bbns-column field="id"
               :hidden="true"
               :editable="false"/>
  <bbns-column field="type"
               :width="120"
               :title="_('Account type')"
               :source="source.types"
               source-value="code"
               />
  <bbns-column field="host"
               :title="_('Host')"/>
  <bbns-column field="login"
               :title="_('Login')"/>
  <bbns-column :title="_('Actions')"
               :width="250"
               :buttons="['edit', 'delete']"/>
</bbn-table>
<script type="text/x-template" id="appui-email-accounts-form-template">
	<bbn-form :source="source.row"
            action="emails/accounts/update">
    <div class="bbn-grid-fields bbn-padded bbn-m">
      <div v-text="_('Type')"/>
      <bbn-dropdown name="type"
                    :source="source.data.types"
                    v-model="source.row.type"/>
      <div v-text="_('Host')"/>
      <bbn-input name="host"
                 v-model="source.row.host"/>
      <div v-text="_('Login')"/>
      <bbn-input name="login"
                 v-model="source.row.login"/>
	  </div>
  </bbn-form>
</script>