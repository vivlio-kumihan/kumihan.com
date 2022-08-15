<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>kumihanBBS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <style type="text/css">
    table,
    th,
    td {
      border: 1px solid #333;
      border-collapse: collapse;
    }

    th {
      background-color: orange;
    }

    .timeline {
      border-left: 1px solid hsl(0, 0%, 90%);
      position: relative;
      list-style: none;
    }

    .timeline .timeline-item {
      position: relative;
    }

    .timeline .timeline-item:after {
      position: absolute;
      display: block;
      top: 0;
    }

    .timeline .timeline-item:after {
      background-color: hsl(0, 0%, 90%);
      top: 8px;
      left: -38px;
      border-radius: 50%;
      height: 11px;
      width: 11px;
      content: "";
    }

    .user {
      font-family: "Hiragino Kaku Gothic ProN", sans-serif;
      font-size: 0.9rem;
      font-weight: 600;
    }
  </style>
</head>

<body>
  <h1>ひとこと掲示板</h1>
  <div class="container">
    <div class="mx-auto" style="margin-top:50px; width: 400px;">
      <section>
        <ul class="timeline">
          <?php if (count($data)) :
            foreach (array_reverse($data) as $row) : ?>
              <li class="timeline-item mb-5">
                <div class="user"><?php echo html_escape($row['name']); ?></div>
                <p class="text-muted mb-2 fw-bold"><?php echo $row['created']; ?></p>
                <p class="text-muted"><?php echo nl2br(html_escape($row['comment'])); ?></p>
              </li>
          <?php endforeach;
          endif; ?>
        </ul>
      </section>
    </div>
  </div>

  <!-- エラーがあった場合に警告を出現させる仕組みは『if』。 -->
  <?php if (count($errs)) {
    foreach ($errs as $err) {
      echo '<p style="color: red">' . $err . '</p>';
    }
  } ?>
  <form action="board.php" method="POST">
    <p>お名前 <input type="text" name="name">（50文字まで）</p>
    <p>ひとこと<textarea name="comment" rows="4" cols="40"></textarea>（200文字まで）</p>
    <input type="submit" value="書き込み">
  </form>

  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@mdo">メッセージ</button>

  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">New message</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form>
            <div class="mb-3">
              <label for="recipient-name" class="col-form-label">Recipient:</label>
              <input type="text" class="form-control" id="recipient-name">
            </div>
            <div class="mb-3">
              <label for="message-text" class="col-form-label">Message:</label>
              <textarea class="form-control" id="message-text"></textarea>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
          <button type="button" class="btn btn-primary">Send message</button>
        </div>
      </div>
    </div>
  </div>



  <script type="text/javascript">
    var exampleModal = document.getElementById('exampleModal')
    exampleModal.addEventListener('show.bs.modal', function (event) {
    // Button that triggered the modal
    var button = event.relatedTarget
    // Extract info from data-bs-* attributes
    var recipient = button.getAttribute('data-bs-whatever')
    // If necessary, you could initiate an AJAX request here
    // and then do the updating in a callback.
    //
    // Update the modal's content.
    var modalTitle = exampleModal.querySelector('.modal-title')
    var modalBodyInput = exampleModal.querySelector('.modal-body input')
  
    modalTitle.textContent = 'New message to ' + recipient
    modalBodyInput.value = recipient
    })
  </script>
</body>

</html>