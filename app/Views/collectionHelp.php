<?php
# Copyright © 2022 Mark Unwin <mark.unwin@gmail.com>
# SPDX-License-Identifier: AGPL-3.0-or-later
include 'shared/collection_functions.php';
?>
        <main class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <?= collection_card_header($meta->collection, $meta->icon, $user); ?>
                </div>
                <div class="card-body text-center">
                    <div class="row">
                        <div class="col-8 offset-2 text-center">
                            <h1><?= ucfirst($meta->collection) ?></h1>
                            <?= $dictionary->about ?>
                            <p>For more detailed information, check the Open-AudIT <a href="https://community.opmantek.com/display/OA/<?= $meta->collection ?>">Knowledge Base</a>.<br /><br /></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-8 offset-2 text-center">
                            <img class="img-fluid" src="<?= base_url() . '/images/' . $meta->collection ?>.png" alt="<?= $meta->collection ?>">
                        </div>
                    </div>
                </div>
            </div>
        </main>