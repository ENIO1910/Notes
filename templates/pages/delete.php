<div class="show">
    <?php
    $note = $params['note'] ?? null; ?>
    <?php
    if ($note): ?>
        <ul>
            <li>
                Tytuł: <?php
                echo $note['title'] ?>
            </li>
            <li>
                Opis: <?php
                echo $note ['description'] ?>
            </li>
            <li>
                Zapisano: <?php
                echo $note ['created_at'] ?>
            </li>
        </ul>
    <form method="post" action="?action=delete">
        <input type="hidden" name="id" value="<?php echo $note['id'] ?>">
        <input type="submit" value="Usuń" />
    </form>

    <?php
    else: ?>
        <div>
            Brak danych do wyświetlenia
        </div>
    <?php
    endif; ?>

    <a href="/Notatki">
        <button>Powrót do listy notatek</button>
    </a>
</div>