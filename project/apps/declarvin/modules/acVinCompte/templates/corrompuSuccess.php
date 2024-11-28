<style>
    #contenu h1 {
        padding-top: 20px;
    }
    #contenu p {
        padding-bottom: 10px;
        font-size: 14px;
    }
</style>
<section id="contenu" style="padding: 30px 20px 70px;">
	<h1>
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-triangle" viewBox="0 0 16 16">
          <path d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.15.15 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.2.2 0 0 1-.054.06.1.1 0 0 1-.066.017H1.146a.1.1 0 0 1-.066-.017.2.2 0 0 1-.054-.06.18.18 0 0 1 .002-.183L7.884 2.073a.15.15 0 0 1 .054-.057m1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767z"/>
          <path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z"/>
        </svg>
        Votre mot de passe est vulnérable
    </h1>

    <p>Suite à un audit de sécurité sur la plateforme DeclarVins, il apparaît que le mot de passe que vous utilisez actuellement n'est pas suffisamment complexe et / ou fait partie d'une liste de mots de passe connue et divulguée sur internet.</p>
    <p>Afin de protéger vos données et éviter tout risque d'accès non autorisé, nous vous demandons donc de <strong>changer votre mot de passe</strong>.</p>
    <p>De manière générale nous vous conseillons de suivre les bonnes pratiques indiquées par le gouvernement en matière de mots de passe : <a style="color: #86005b;" target="_blank" href="https://www.cybermalveillance.gouv.fr/tous-nos-contenus/bonnes-pratiques/mots-de-passe">Les Bonnes Pratiques</a></p>
    <p>L'équipe DeclarVins</p>
    <div class="popup_form contenu clearfix">
        <p class="ligne_form_btn" style="text-align: center;">
            <a class="btn_valider" href="<?php echo url_for('compte_password', ['login' => $sf_user->getCompte()->login, 'rev' => $sf_user->getCompte()->_rev]) ?>">Changer mon mot de passe</a>
            <a style="text-transform:none;" href="<?php echo ($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR))? url_for('admin') : url_for('tiers'); ?>">Me le rappeler à la prochaine connexion</a>
        </p>
    </div>
</section>
