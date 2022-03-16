<?=$render('header', ['loggedUser' => $loggedUser, 'flash' => $flash]);?>  

    <section class="container main">
      <?=$render('sidebar', ['activeMenu'=>'config']);?>
      <section class="config">       
        <div class="row">
            <div class="side pr-5 profile-config">
              <h1>Configurações</h1>                
                <form method="POST" action="<?=$base;?>/config" enctype="multipart/form-data">
                    <?php if(!empty($flash)):?>
                      <div class="flash"><?php echo $flash;?></div>
                    <?php endif; ?>

                    <label for="avatar">Avatar</label>
                    <input type="file" name="avatar"/><br/>
                    <img class="image-edit" src="<?=$base;?>/media/avatars/<?=$user->avatar;?>" alt=""><br/>

                    <label for="capa">Nova capa</label>
                    <input type="file" name="cover"/><br/>
                    <img class="image-edit" src="<?=$base;?>/media/covers/<?=$user->cover;?>" alt="">

                    <hr/>

                    <label for="nome_ompleto">Nome completo</label>
                    <input type="text" name="name" value="<?=$loggedUser->name?>">

                    <label for="data_nasc">Data de nascimento</label>
                    <input type="text" name="birthdate" id="birthdate" value="<?=date('d/m/Y', strtotime($loggedUser->birthdate));?>">

                    <label for="email">E-mail</label>
                    <input type="email" name="email" value="<?=$loggedUser->email;?>">

                    <label for="cidade">Cidade</label>
                    <input type="text" name="city" value="<?=$loggedUser->city;?>">

                    <label for="trabalho">Trabalho</label>
                    <input type="text" name="work" value="<?=$loggedUser->work;?>">

                    <hr>

                    <label for="password">Nova senha</label>
                    <input type="password" value="">

                    <label for="confirm-password">Confirmar nova senha</label>
                    <input type="password" name="confirm-password" value="">

                    <input class="button" type="submit" value="Salvar" />
                </form>                             
        </div>
    </section>
  </section>

  <script src="https://unpkg.com/imask" ></script>
<script>
IMask(
    document.getElementById('birthdate'),
    {
        mask:'00/00/0000'
    }
);
</script>
<?=$render('footer');?>