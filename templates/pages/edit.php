<div>
    <h3>Edycja notatki</h3>
    <div>
        <?php
            if (!empty($params['note'])):
        ?>
        <?php
            $note = $params['note'] ?? null;
        ?>
        <form class="note-form" action="?action=edit" method="post">
            <input type="hidden" name="id" value="<?php echo $note['id'] ?>"
            <ul>
                <li>
                    <label> Tytuł <span class="required">*</span></label>
                    <input type="text" name="title" value="<?php echo $note['title']?>" class="field-long"/>
                </li>
                <li>
                    <label> Opis </label>
                    <textarea name="description" id="field5" class="field-long field-textarea"><?php echo $note['description']?></textarea>
                </li>
                <li>
                    <input type="submit" value="Submit"/>
                </li>
            </ul>
        </form>

        <?php else: ?>
            <div>
                Brak danych do wyświetlenia
            </div>
            <div>
                <a href="/Notatki">
                    <button>Powrót do listy notatek</button>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
