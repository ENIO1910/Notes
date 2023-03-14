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
        <a href="?action=edit&id=<?php echo $note['id'] ?>">
            <button>Edytuj</button>
        </a>
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