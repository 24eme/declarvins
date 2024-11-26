<style>
    #contenu h1 {
        padding-top: 20px;
    }
    #contenu p, #contenu ul li {
        padding-bottom: 10px;
        font-size: 14px;
    }
    #contenu ul {
        list-style: inside;
        margin-bottom: 10px;
    }
    #contenu li a {
        color: #86005b;
    }
</style>
<section id="contenu" style="padding: 30px 20px 70px;">
	<h1>
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-triangle" viewBox="0 0 16 16">
          <path d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.15.15 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.2.2 0 0 1-.054.06.1.1 0 0 1-.066.017H1.146a.1.1 0 0 1-.066-.017.2.2 0 0 1-.054-.06.18.18 0 0 1 .002-.183L7.884 2.073a.15.15 0 0 1 .054-.057m1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767z"/>
          <path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z"/>
        </svg>
        Votre mot de passe est compromis
    </h1>

    <p>Nous souhaitons attirer votre attention sur un <strong>problème de sécurité</strong> concernant votre compte.</p>
    <p>Après vérification, il apparaît que le mot de passe que vous utilisez actuellement est présent dans une base de données divulguée suite à une fuite de données (<strong>data leak</strong>).</p>
    <p>Cela signifie que votre mot de passe est désormais connu de personnes malveillantes et représente <strong>une faille importante pour la sécurité de votre compte</strong>.</p>

    <p>Afin de protéger vos données et éviter tout accès non autorisé, nous vous invitons à agir rapidement :</p>
    <ul>
        <li>En <strong>changeant votre mot de passe</strong> immédiatement : <a href="<?php echo url_for('compte_password', ['login' => $sf_user->getCompte()->login, 'rev' => $sf_user->getCompte()->_rev]) ?>">Redéfinir mon mot de passe</a></li>
        <li>En <strong>mettant à jour vos autres comptes</strong> si ce même mot de passe a été utilisé sur d’autres plateformes.</li>
    </ul>
    <p>Nous vous remercions de votre vigilance et restons disponibles pour toute question complémentaire.</p>
    <p>L'équipe DeclarVins</p>
    <div class="popup_form contenu clearfix">
        <p class="ligne_form_btn" style="text-align: center;">
            <a class="btn_valider" href="<?php echo url_for('compte_password', ['login' => $sf_user->getCompte()->login, 'rev' => $sf_user->getCompte()->_rev]) ?>">Modifier mon mot de passe</a>
            <a style="text-transform:none;" href="<?php echo ($sf_user->hasCredential(myUser::CREDENTIAL_OPERATEUR))? url_for('admin') : url_for('tiers'); ?>">ignorer ></a>
        </p>
    </div>
</section>
