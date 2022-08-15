<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>kumihanBBS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
  <div class="container">
    <div class="mx-auto" style="margin-top:50px; width: 400px;">
      <section class="timeline">
        <style>
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

        <ul class="timeline">
          <li class="timeline-item mb-5">
            <div class="user">高広信之</h5>
              <p class="text-muted mb-2 fw-bold">11 March 2020</p>
              <p class="text-muted">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Sit
                necessitatibus adipisci, ad alias, voluptate pariatur officia
                repellendus repellat inventore fugit perferendis totam dolor
                voluptas et corrupti distinctio maxime corporis optio?
              </p>
          </li>

          <li class="timeline-item mb-5">
            <div class="user">木田滋之</h5>
              <p class="text-muted mb-2 fw-bold">19 March 2020</p>
              <p class="text-muted">
                Quisque ornare dui nibh, sagittis egestas nisi luctus nec. Sed
                aliquet laoreet sapien, eget pulvinar lectus maximus vel.
                Phasellus suscipit porta mattis.
              </p>
          </li>

          <li class="timeline-item mb-5">
            <div class="user">竹中丘像</h5>
              <p class="text-muted mb-2 fw-bold">24 June 2020</p>
              <p class="text-muted">
                Orci varius natoque penatibus et magnis dis parturient montes,
                nascetur ridiculus mus. Nulla ullamcorper arcu lacus, maximus
                facilisis erat pellentesque nec. Duis et dui maximus dui aliquam
                convallis. Quisque consectetur purus erat, et ullamcorper sapien
                tincidunt vitae.
              </p>
          </li>

          <li class="timeline-item mb-5">
            <div class="user">清水妙子</h5>
              <p class="text-muted mb-2 fw-bold">15 October 2020</p>
              <p class="text-muted">
                Nulla ac tellus convallis, pulvinar nulla ac, fermentum diam. Sed
                et urna sit amet massa dapibus tristique non finibus ligula. Nam
                pharetra libero nibh, id feugiat tortor rhoncus vitae. Ut suscipit
                vulputate mattis.
              </p>
          </li>
        </ul>
      </section>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    </div>
  </div>
</body>

</html>