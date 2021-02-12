<form 
    action="<?php echo htmlspecialchars(BASE . "search");?>" 
    class="card-body" 
    method="GET"
>
    <div class="input-field mt-2">
        <label for="autocomplete-input">Cidade</label>
        <input 
            type="text" 
            placeholder="Ex..: Rio de Janeiro,BR,+55" 
            id="autocomplete-input" 
            class="autocomplete"
            name="q"
            autocomplete="off"
            value="<?=$search?>"
            required
            minlength="2"
            maxlength="60"
        >
    </div>
    <div class="right-align">
        <button class="waves-effect waves-light btn">Pesquisar</button>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', async function()
    {
        let data = await fetch("<?=BASE?>autocomplete", {method: "POST"})
        .then(async (res) => res.json())
        .then(async (data) => {
            let res = {};

            data.map((value) => {
                res[value] = null;
            });

            return res;
        })
        .catch(() => {});

        M.Autocomplete.init(document.querySelectorAll('.autocomplete'), {
            data: data
        });
    });
</script>