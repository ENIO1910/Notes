<div class="list">
    <section>
        <div class="message">
            <?php
            if (!empty($params['before'])) {
                switch ($params['before']) {
                    case 'created':
                        echo "Utworzono notatkę";
                        break;
                    case 'edited':
                        echo "Edytowano notatkę";
                        break;
                    case 'deleted':
                        echo "Usunięto notatkę";
                        break;
                    case 'notFound':
                        echo "Nie znaleziono notatki";
                        break;
                    case 'missingNoteId':
                        echo "Niepoprawny identyfikator notatki";
                        break;
                }
            }
            ?>
        </div>

        <?php

        $sort = $params['sort'] ?? [];
        $by = $sort['by'] ?? 'title';
        $order = $sort['order'] ?? 'DESC';

        $page = $params['page'] ?? [];

        $size = $page['size'] ?? 10;
        $currentPage = $page['number'] ?? 1;
        $pages = $page['pages'] ?? 1;

        $phrase = $params['phrase'] ?? null;
        ?>


        <div>
            <form class="settings-form" action="/Notatki" method="GET">
                <div>
                    <label>
                        SZUKAJ: <input type="text" name="phrase" value="<?php echo $phrase?>"/>
                    </label>

                </div>
                <div>
                    Sortuj po:
                </div>
                <div>
                    <label>
                        Tytuł: <input name="sortby" type="radio" value="title" <?php echo $by === 'title' ? 'checked' : '' ?> />
                    </label>
                    <label>
                        Dacie: <input name="sortby" type="radio" value="created_at" <?php echo $by === 'created_at' ? 'checked' : '' ?> />
                    </label>

                </div>
                <div>
                    Kierunek sortowania
                </div>
                <div>
                    <label>
                        Rosnąco <input name="sortOrder" type="radio" value="asc" <?php echo $order === 'asc' ? 'checked' : '' ?> />
                    </label>
                    <label>
                        Malejąco <input name="sortOrder" type="radio" value="desc" <?php echo $order === 'desc' ? 'checked' : '' ?> />
                    </label>
                </div>
                <div>
                    <div>Maksymalna ilość rekordów</div>
                    <label>
                        1 <input name="pageSize" type="radio" value="1" <?php echo $size === 1 ? 'checked' : '' ?>/>
                    </label>
                    <label>
                        5 <input name="pageSize" type="radio" value="5" <?php echo $size === 5 ? 'checked' : '' ?>/>
                    </label>
                    <label>
                        10 <input name="pageSize" type="radio" value="10" <?php echo $size === 10 ? 'checked' : '' ?>/>
                    </label>
                    <label>
                        25 <input name="pageSize" type="radio" value="25" <?php echo $size === 25 ? 'checked' : '' ?>/>
                    </label>
                </div>
                <input type="submit" value="Sortuj" />

            </form>
        </div>

        <div class="tbl-header">
            <table cellpadding="0" cellspacing="0" border="0">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Tytuł</th>
                    <th>Data</th>
                    <th>Opcje</th>
                </tr>
                </thead>
            </table>
        </div>
        <div class="tbl-content">
            <table cellpadding="0" cellspacing="0" border="0">
                <tbody>
                <?php
                foreach ($params['notes'] ?? [] as $note): ?>
                    <tr>
                        <td>
                            <?php
                            echo (int)$note['id']
                            ?>
                        </td>
                        <td style="text-align:center">
                            <?php
                            echo $note['title']
                            ?>
                        </td>
                        <td>
                            <?php
                            echo $note['created_at']
                            ?>
                        </td>
                        <td>
                            <a href="?action=show&id=<?php
                            echo (int)$note['id'] ?>">
                                <button>Pokaż</button>
                            </a>
                            <a href="?action=delete&id=<?php
                            echo (int)$note['id'] ?>">
                                <button>Usuń</button>
                            </a>
                        </td>
                    </tr>

                <?php
                endforeach; ?>
                </tbody>
            </table>
        </div>
        <ul class="pagination">
            <?php
                $paginationUrl = "&phrase=$phrase&pageSize=$size&sortby=$by&sortOrder=$order"
            ?>
            <?php if($currentPage != 1): ?>
            <li>
                <a href="/Notatki?page=<?php echo $currentPage - 1 . $paginationUrl ?>">
                    <button><<</button>
                </a>
            </li>
            <?php endif;?>
            <?php for($i = 1; $i <= $pages; $i++): ?>
                <li>
                    <a href="/Notatki?page=<?php echo $i . $paginationUrl ?>">
                        <button><?php echo $i ?></button>
                    </a>
                </li>

            <?php endfor; ?>
            <?php if($currentPage < $pages): ?>
            <li>
                <a href="/Notatki?page=<?php echo $currentPage + 1 . $paginationUrl?>">
                    <button>>></button>
                </a>
            </li>
            <?php endif;?>
        </ul>
    </section>
</div>
