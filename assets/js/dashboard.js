  // Funções para ações
  function editarDespesa(id) {
    window.location.href = '../pages/Despesas/editar_despesa.php?id=' + id;
  }

   function editarCategoria(id) {
    window.location.href = '../Categorias/editar_categoria.php?id=' + id;
  }

  function deletarCategoria(id) {
    if (confirm('Tem certeza que deseja excluir esta Categoria?')) {
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = '../../scripts/Delete/DeletarCategoria.php';
      
      const input = document.createElement('input');
      input.type = 'hidden';
      input.name = 'id';
      input.value = id;
      
      form.appendChild(input);
      document.body.appendChild(form);
      form.submit();
    }
  }  

  function deletarDespesa(id) {
    if (confirm('Tem certeza que deseja excluir esta despesa?')) {
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = '../scripts/Delete/DeletarDespesa.php';
      
      const input = document.createElement('input');
      input.type = 'hidden';
      input.name = 'id';
      input.value = id;
      
      form.appendChild(input);
      document.body.appendChild(form);
      form.submit();
    }
  }
  