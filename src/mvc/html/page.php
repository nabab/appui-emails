<div class="appui-emails bbn-overlay">
  <bbn-router class="appui_emails_nav"
              :scrollable="true"
              :autoload="true"
              :nav="true"
  >
    <bbns-container url="home"
                    :load="true"
                    title="<?=_('Mailings')?>"
                    icon="nf nf-fa-newspaper"
                    :static="true"
    ></bbns-container>
  </bbn-router>
</div>