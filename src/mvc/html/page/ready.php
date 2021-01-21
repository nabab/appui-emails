<appui-email-table :source="source" 
                    tableSource="data/emails"
                    :filters="{
                      logic: 'AND',
                      conditions: [{
                        field: 'status',
                        operator: 'eq',
                        value: 'ready'
                      }]
                    }"
                    context="ready"
></appui-email-table>