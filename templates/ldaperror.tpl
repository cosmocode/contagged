{if $LDAPERRORS != ''}
<script>
  window.alert('{$lang.err_ldap}:\n\n{$LDAPERRORS|escape:quotes}');
</script>
{/if}
