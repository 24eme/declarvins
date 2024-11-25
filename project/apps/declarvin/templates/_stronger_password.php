<div>
<style>
    .password-rules {
        margin-bottom: 1rem;
    }
    .password-rules h2 {
        font-size: 0.85rem;
        margin-bottom: .5rem;
        font-weight: 500;
        line-height: 1.2;
    }
    .list-group {
        display: flex;
        flex-direction: column;
        padding-left: 0;
        margin-bottom: 0;
        border-radius: .25rem !important;
    }
    .list-group-item {
        position: relative;
        display: block;
        padding: .4rem 1rem;
        opacity: 0.75;
        text-decoration: none;
        border: 1px solid rgba(0,0,0,.125);
        margin: 0 5px 0 0 !important;
        background-repeat: no-repeat;
        background-position: left;
        background-image: url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNiIgaGVpZ2h0PSIxNiIgZmlsbD0iY3VycmVudENvbG9yIiBjbGFzcz0iYmkgYmkteCIgdmlld0JveD0iMCAwIDE2IDE2Ij4KICA8cGF0aCBkPSJNNC42NDYgNC42NDZhLjUuNSAwIDAgMSAuNzA4IDBMOCA3LjI5M2wyLjY0Ni0yLjY0N2EuNS41IDAgMCAxIC43MDguNzA4TDguNzA3IDhsMi42NDcgMi42NDZhLjUuNSAwIDAgMS0uNzA4LjcwOEw4IDguNzA3bC0yLjY0NiAyLjY0N2EuNS41IDAgMCAxLS43MDgtLjcwOEw3LjI5MyA4IDQuNjQ2IDUuMzU0YS41LjUgMCAwIDEgMC0uNzA4Ii8+Cjwvc3ZnPg==");
    }
    .list-group-item.checked {
        background-image: url("data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+CjxzdmcKICAgd2lkdGg9IjE2IgogICBoZWlnaHQ9IjE2IgogICBmaWxsPSJjdXJyZW50Q29sb3IiCiAgIGNsYXNzPSJiaSBiaS1jaGVjayIKICAgdmlld0JveD0iMCAwIDE2IDE2IgogICB2ZXJzaW9uPSIxLjEiCiAgIGlkPSJzdmc0IgogICBzb2RpcG9kaTpkb2NuYW1lPSJjaGVjay5zdmciCiAgIGlua3NjYXBlOnZlcnNpb249IjEuMi4yIChiMGE4NDg2NTQxLCAyMDIyLTEyLTAxKSIKICAgeG1sbnM6aW5rc2NhcGU9Imh0dHA6Ly93d3cuaW5rc2NhcGUub3JnL25hbWVzcGFjZXMvaW5rc2NhcGUiCiAgIHhtbG5zOnNvZGlwb2RpPSJodHRwOi8vc29kaXBvZGkuc291cmNlZm9yZ2UubmV0L0RURC9zb2RpcG9kaS0wLmR0ZCIKICAgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIgogICB4bWxuczpzdmc9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KICA8ZGVmcwogICAgIGlkPSJkZWZzOCIgLz4KICA8c29kaXBvZGk6bmFtZWR2aWV3CiAgICAgaWQ9Im5hbWVkdmlldzYiCiAgICAgcGFnZWNvbG9yPSIjZmZmZmZmIgogICAgIGJvcmRlcmNvbG9yPSIjMDAwMDAwIgogICAgIGJvcmRlcm9wYWNpdHk9IjAuMjUiCiAgICAgaW5rc2NhcGU6c2hvd3BhZ2VzaGFkb3c9IjIiCiAgICAgaW5rc2NhcGU6cGFnZW9wYWNpdHk9IjAuMCIKICAgICBpbmtzY2FwZTpwYWdlY2hlY2tlcmJvYXJkPSIwIgogICAgIGlua3NjYXBlOmRlc2tjb2xvcj0iI2QxZDFkMSIKICAgICBzaG93Z3JpZD0iZmFsc2UiCiAgICAgaW5rc2NhcGU6em9vbT0iNTIuNDM3NSIKICAgICBpbmtzY2FwZTpjeD0iNC4yMzM2MTE0IgogICAgIGlua3NjYXBlOmN5PSI3Ljk5MDQ2NDgiCiAgICAgaW5rc2NhcGU6d2luZG93LXdpZHRoPSIxOTIwIgogICAgIGlua3NjYXBlOndpbmRvdy1oZWlnaHQ9IjEwMTEiCiAgICAgaW5rc2NhcGU6d2luZG93LXg9IjAiCiAgICAgaW5rc2NhcGU6d2luZG93LXk9IjMyIgogICAgIGlua3NjYXBlOndpbmRvdy1tYXhpbWl6ZWQ9IjEiCiAgICAgaW5rc2NhcGU6Y3VycmVudC1sYXllcj0ic3ZnNCIgLz4KICA8cGF0aAogICAgIGQ9Ik0xMC45NyA0Ljk3YS43NS43NSAwIDAgMSAxLjA3IDEuMDVsLTMuOTkgNC45OWEuNzUuNzUgMCAwIDEtMS4wOC4wMkw0LjMyNCA4LjM4NGEuNzUuNzUgMCAxIDEgMS4wNi0xLjA2bDIuMDk0IDIuMDkzIDMuNDczLTQuNDI1eiIKICAgICBpZD0icGF0aDIiCiAgICAgc3R5bGU9ImZpbGw6IzE5ODc1NDtmaWxsLW9wYWNpdHk6MSIgLz4KPC9zdmc+Cg==");
        color: #198754;
        opacity: 1;
    }
    .list-group-item + .list-group-item {
        border-top-width: 0;
    }
    .show-password{
        position: relative;
        right: 18px;
        cursor: pointer;
        display: inline-block;
        width: 16px;
        height: 16px;
        background-repeat: no-repeat;
        background-image: url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNiIgaGVpZ2h0PSIxNiIgZmlsbD0iY3VycmVudENvbG9yIiBjbGFzcz0iYmkgYmktZXllIiB2aWV3Qm94PSIwIDAgMTYgMTYiPgogIDxwYXRoIGQ9Ik0xNiA4cy0zLTUuNS04LTUuNVMwIDggMCA4czMgNS41IDggNS41UzE2IDggMTYgOE0xLjE3MyA4YTEzIDEzIDAgMCAxIDEuNjYtMi4wNDNDNC4xMiA0LjY2OCA1Ljg4IDMuNSA4IDMuNXMzLjg3OSAxLjE2OCA1LjE2OCAyLjQ1N0ExMyAxMyAwIDAgMSAxNC44MjggOHEtLjA4Ni4xMy0uMTk1LjI4OGMtLjMzNS40OC0uODMgMS4xMi0xLjQ2NSAxLjc1NUMxMS44NzkgMTEuMzMyIDEwLjExOSAxMi41IDggMTIuNXMtMy44NzktMS4xNjgtNS4xNjgtMi40NTdBMTMgMTMgMCAwIDEgMS4xNzIgOHoiLz4KICA8cGF0aCBkPSJNOCA1LjVhMi41IDIuNSAwIDEgMCAwIDUgMi41IDIuNSAwIDAgMCAwLTVNNC41IDhhMy41IDMuNSAwIDEgMSA3IDAgMy41IDMuNSAwIDAgMS03IDAiLz4KPC9zdmc+");
    }
    .show-password.hide{
        background-image: url("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNiIgaGVpZ2h0PSIxNiIgZmlsbD0iY3VycmVudENvbG9yIiBjbGFzcz0iYmkgYmktZXllLXNsYXNoIiB2aWV3Qm94PSIwIDAgMTYgMTYiPgogIDxwYXRoIGQ9Ik0xMy4zNTkgMTEuMjM4QzE1LjA2IDkuNzIgMTYgOCAxNiA4cy0zLTUuNS04LTUuNWE3IDcgMCAwIDAtMi43OS41ODhsLjc3Ljc3MUE2IDYgMCAwIDEgOCAzLjVjMi4xMiAwIDMuODc5IDEuMTY4IDUuMTY4IDIuNDU3QTEzIDEzIDAgMCAxIDE0LjgyOCA4cS0uMDg2LjEzLS4xOTUuMjg4Yy0uMzM1LjQ4LS44MyAxLjEyLTEuNDY1IDEuNzU1cS0uMjQ3LjI0OC0uNTE3LjQ4NnoiLz4KICA8cGF0aCBkPSJNMTEuMjk3IDkuMTc2YTMuNSAzLjUgMCAwIDAtNC40NzQtNC40NzRsLjgyMy44MjNhMi41IDIuNSAwIDAgMSAyLjgyOSAyLjgyOXptLTIuOTQzIDEuMjk5LjgyMi44MjJhMy41IDMuNSAwIDAgMS00LjQ3NC00LjQ3NGwuODIzLjgyM2EyLjUgMi41IDAgMCAwIDIuODI5IDIuODI5Ii8+CiAgPHBhdGggZD0iTTMuMzUgNS40N3EtLjI3LjI0LS41MTguNDg3QTEzIDEzIDAgMCAwIDEuMTcyIDhsLjE5NS4yODhjLjMzNS40OC44MyAxLjEyIDEuNDY1IDEuNzU1QzQuMTIxIDExLjMzMiA1Ljg4MSAxMi41IDggMTIuNWMuNzE2IDAgMS4zOS0uMTMzIDIuMDItLjM2bC43Ny43NzJBNyA3IDAgMCAxIDggMTMuNUMzIDEzLjUgMCA4IDAgOHMuOTM5LTEuNzIxIDIuNjQxLTMuMjM4bC43MDguNzA5em0xMC4yOTYgOC44ODQtMTItMTIgLjcwOC0uNzA4IDEyIDEyeiIvPgo8L3N2Zz4=");
    }
     input[type='password'].unvalid {
         border-color: #e35d6a !important;
     }
    .password-unvalid {
        color: #e35d6a;
        margin-bottom: 5px;
        display:none;
    }
</style>

<div class="password-rules">
    <p class="password-unvalid">Toutes les contraintes du mot de passe doivent être respectées</p>
    <h2>Le mot de passe doit contenir</h2>
    <ul class="list-group">
        <li class="list-group-item" data-regex=".{8,}">8 caractères minimum</li>
        <li class="list-group-item" data-regex="[0-9]">Au moins 1 chiffre</li>
        <li class="list-group-item" data-regex="[A-Z]">Au moins 1 lettre majuscule</li>
        <li class="list-group-item" data-regex="[a-z]">Au moins 1 lettre minuscule</li>
        <li class="list-group-item" data-regex="[^A-Za-z0-9]">Au moins 1 caractère spécial (@, #, $, ...)</li>
    </ul>
</div>

<script type="text/javascript">
document.addEventListener("DOMContentLoaded", () => {
    const passwordInput = document.getElementById("<?php echo $inputPasswordTarget ?>");
    const showPasswordIcon = document.createElement("i");
    showPasswordIcon.classList.add("show-password");
    showPasswordIcon.addEventListener("click", () => {
        showPasswordIcon.classList.toggle('hide');
        passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
    });
    passwordInput.after(showPasswordIcon);
    const rules = Array.from(document.querySelector('.password-rules .list-group').children);

    const validatePassword = (password) => {
        let valid = true;
        rules.forEach((rule) => {
            const regex = new RegExp(rule.dataset.regex);
            if (regex.test(password)) {
                rule.classList.add("checked");
            } else {
                valid = false;
                rule.classList.remove("checked");
            }
        });
        if (valid) {
            passwordInput.classList.remove("unvalid");
            document.querySelector('.password-rules .password-unvalid').style.display = 'none';
        }
        return valid;
    };
    passwordInput.addEventListener("input", (e) => validatePassword(e.target.value));
    passwordInput.closest("form").addEventListener("submit", (e) => {
        if (!validatePassword(passwordInput.value)) {
            e.preventDefault();
            passwordInput.classList.add("unvalid");
            document.querySelector('.password-rules .password-unvalid').style.display = 'block';
        }
    });
});
</script>
</div>
