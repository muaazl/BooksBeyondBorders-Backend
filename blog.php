<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BBB - Blog</title>
    <link rel="icon" type="image/x-icon" href="images/slogo.ico">

    <!-- font-awesome cdn link -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />

    <!-- custom css file link -->
    <link rel="stylesheet" href="assets/style.css">

</head>

<body>

<?php
include 'include/header.php';
?>

    <section id="page-header" class="blog-header">
        <h2>#StoryThreads</h2>
        <p>Book Reviews, Playlists, and Creative Inspirations – Dive In!</p>
    </section>

    <section id="blog">
        <div class="blog-box">
            <div class="blog-img">
                <img id="mainimg" src="images/blog/b1.jpg" alt="">
            </div>
            <div class="blog-details">
                <h4>Top 3 Must-Read Books Of All Time</h4>
                <p>Explore this month’s best new releases and hidden gems across all genres. From thrilling mysteries to heartfelt romances, this curated list of books is sure to captivate every type of reader. Don’t miss out on these top picks!</p>
                <div class="hidden-content collapsed" id="content1"><hr>
                    <p><strong>The Nightingale by Kristin Hannah:</strong> Set during World War II, this unforgettable novel tells the story of two sisters in France and their struggles for survival and resistance under Nazi occupation. It delves into the complexities of love, sacrifice, and resilience. With vivid imagery and heart-wrenching moments, Kristin Hannah brings the untold stories of women in war to the forefront.</p>
                    <p><strong>It Ends with Us by Colleen Hoover:</strong> A profound and emotional journey, this novel follows Lily Bloom as she navigates love, heartbreak, and self-discovery. Exploring themes of domestic abuse, forgiveness, and second chances, Colleen Hoover masterfully creates a story that is both heartbreaking and hopeful, leaving readers deeply moved by Lily’s strength and courage.</p>
                    <p><strong>The Love Hypothesis by Ali Hazelwood:</strong> A delightful romantic comedy set in the world of academia, this book follows Olive Smith, a third-year Ph.D. candidate, and her unlikely fake-dating arrangement with Dr. Adam Carlsen. Filled with humor, witty banter, and a healthy dose of science, this story showcases the challenges of balancing ambition and love while capturing the magic of finding someone who truly understands you.</p>
                    <hr><img id="mainimg" src="images/blog/b1.1.jpg" alt="Book collection image" style="width:100%; margin-top:20px;">
                    <p>These three novels are just the tip of the iceberg. Whether you’re a fan of historical fiction, emotional dramas, or lighthearted romances, this list promises a captivating read for everyone. Get ready to lose yourself in stories that will make you laugh, cry, and reflect long after you’ve turned the last page.</p>
                </div>
                <button onclick="toggleContent('content1', this)">CONTINUE READING</button>
            </div>
            <h1>13/01</h1>
        </div>
        <div class="blog-box">
            <div class="blog-img">
                <img id="mainimg" src="images/blog/b2.jpg" alt="">
            </div>
            <div class="blog-details">
                <h4>Must-Listen Playlist: Enhance Your Reading Experience</h4>
                <p>Discover the perfect playlist to accompany your favorite books. From soothing melodies to instrumental tracks, these songs are designed to create a peaceful atmosphere that lets you dive deep into your reading journey.</p>
                <div class="hidden-content collapsed" id="content2">
                    <p>Whether you're flipping through the pages of a romantic novel, immersing yourself in a thrilling mystery, or exploring the worlds of fantasy, this playlist is your ideal reading companion. It features soft piano compositions, mellow acoustic guitar pieces, and gentle instrumental tracks that enhance focus and relax the mind. Imagine the serene notes of a piano as you lose yourself in the delicate prose of your book.</p>
                    <p>This playlist is curated to match the ebb and flow of a story’s emotion. Subtle strings set the tone for suspenseful chapters, while uplifting melodies perfectly accompany moments of triumph or love. With carefully selected tracks, the playlist doesn’t distract but harmonizes seamlessly with the rhythm of your reading, creating an immersive experience.</p>
                    <p>Perfect for quiet afternoons or late-night reading sessions, this playlist aims to transport you into the heart of the story. Each track has been chosen to elevate your mood, sharpen your imagination, and help you focus. From cinematic scores to lo-fi beats, there’s something here for every type of reader looking to add a little melody to their literary escape.</p>
                    <!-- Embed Spotify Playlist -->
                    <iframe style="border-radius:12px" src="https://open.spotify.com/embed/playlist/2pSOXcKlz8pxU4LARZhIwp?utm_source=generator" width="100%" height="500" frameBorder="0" allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy"></iframe>
                    <p>Click above to start listening. Let the music take you on an unforgettable journey alongside the words of your favorite authors. Happy reading and listening!</p>
                </div>
                <button onclick="toggleContent('content2', this)">CONTINUE READING</button>
            </div>
            
            <h1>12/01</h1>
        </div>
        <div class="blog-box">
            <div class="blog-img">
                <img id="mainimg" src="images/blog/b3.jpg" alt="">
            </div>
            <div class="blog-details">
                <h4>Poetic Reflections: A Poem on the Journey of Reading</h4>
                <p>There’s something magical about the way words on a page can transport you to a different world. This poetry celebrates the beauty of books and the unique journey of every reader who ventures into their pages.</p>
                <div class="hidden-content collapsed" id="content3">
                    <p>Reading is like stepping into a river of words, where each phrase carries you closer to something profound...</p>
                    <hr>
                    <div class="column">
                        <div class="text-content">
                            <h4>Whispers Between Pages</h4>
                            <p>
                                Beneath the cover, a world unfolds,<br>
                                Secrets whispered, tales retold.<br>
                                Each chapter breathes, alive and true,<br>
                                A thousand lives, all lived through you.
                                <br><br>
                                With every turn, a dream takes flight,<br>
                                Darkness fades in paper light.<br>
                                Between the pages, hearts entwine,<br>
                                Your soul with theirs, a thread divine.
                            </p>
                        </div><div id="blogimg">
                        <img id="side" src="images/blog/b3.1.jpg" alt="Poem Image 1"></div>
                    </div>
                
                    <div class="column">
                        <div class="text-content">
                            <h4>A Reader’s Solace</h4>
                            <p>
                                In the quiet of the night,<br>
                                With a story as my light,<br>
                                I wander lands both far and near,<br>
                                With every word, the world is clear.
                                <br><br>
                                The books, they hold my truest friends,<br>
                                Whose wisdom never falters, bends.<br>
                                Through ink and time, they softly speak,<br>
                                To lift me up when I feel weak.
                            </p>
                        </div><div id="blogimg">
                        <img id="side" src="images/blog/b3.2.jpg" alt="Poem Image 2"></div>
                    </div>
                    <p>Poetry like this reminds us of the emotional resonance that books bring into our lives...</p>
                    <hr>
                <div><br><br>
                    <h4>Poetic Visuals</h4>
                    <img id="mainimg" src="images/blog/b3.3.jpg" alt="Poetic reading inspiration" style="width:100%; margin-top:20px;">
                    <p>Find your inspiration and lose yourself in the beauty of both words and worlds...</p>
                </div></div>
                <button onclick="toggleContent('content3', this)">CONTINUE READING</button>
            </div>
            
            <h1>16/01</h1>
        </div>

        <div class="blog-box">
            <div class="blog-img">
                <img id="mainimg" src="images/blog/b4.jpg" alt="">
            </div>
            <div class="blog-details">
                <h4>The Art Within the Story: Illustrations in Novels</h4>
                <p>Illustrations and sketches in novels bring a new dimension to storytelling, transforming words into visual journeys. These artistic creations not only enhance the narrative but also offer readers a glimpse into the author's imagination. Discover the beauty of illustrated novels with these captivating highlights!</p>
                <div class="hidden-content collapsed" id="content5">
                    <p><strong>Bringing Stories to Life Through Art</strong><br><br>
                    Illustrations in novels have a unique way of breathing life into characters and settings. From delicate pencil sketches to vibrant watercolor artworks, these images offer a deeper connection to the story. They evoke emotions, highlight pivotal moments, and immerse readers in a visual symphony that complements the written word.</p>
                    <p>Sketches often reveal nuances of a character’s personality, attire, or mood that words alone might not convey. Whether it’s a protagonist’s determined gaze or the intricate detailing of a fantastical creature, these illustrations provide insights into the narrative, creating a richer and more engaging experience for readers.</p>
                    <p>Many novels have become timeless classics not just for their plots but also for their unforgettable illustrations. Think of the whimsical drawings in *Alice’s Adventures in Wonderland* or the haunting sketches in *The Illustrated Man*. These artworks remain etched in our memories, serving as visual bookmarks of the stories they accompany.</p>
                    
                    <h4>Visual Inspirations</h4>
                    <div class="image-gallery">
                        <img src="images/blog/b5.1.jpg" class="thumbnail" onclick="showOverlay(this)">
                        <img src="images/blog/b5.2.jpg" class="thumbnail" onclick="showOverlay(this)">
                        <img src="images/blog/b5.3.jpg" class="thumbnail" onclick="showOverlay(this)">
                    </div>
                    <div id="overlay" class="overlay" onclick="closeOverlay()">
                        <img id="overlay-img" src="" alt="Expanded view">
                    </div>
                </div>
                <button onclick="toggleContent('content5', this)">CONTINUE READING</button>
            </div>
            <h1>10/03</h1>
        </div>

        <div class="blog-box">
            <div class="blog-img">
                <img id="mainimg" src="images/blog/b5.jpg" alt="">
            </div>
            <div class="blog-details">
                <h4>Featured Quotes & Self Reflections: Wisdom from Books</h4>
                <p>Books hold timeless wisdom that resonates through the ages. Here are some inspiring quotes and self reflections I’ve cherished from my reading adventures:</p>
                
                <div class="hidden-content collapsed" id="content4">
                    <div class="quote-section">
                        <h4>1. Embrace the Journey</h4>
                        <p>“It is good to have an end to journey toward; but it is the journey that matters, in the end.” ~ Ursula K. Le Guin
                            <br><br>
                            This reminds us to focus on growth and experiences along the way, rather than rushing to achieve our goals.</p>
                    </div>
                    <div class="quote-section">
                        <h4>2. Strength in Difficult Times</h4>
                        <p>“Happiness can be found, even in the darkest of times, if one only remembers to turn on the light.” ~ J.K. Rowling
                            <br><br>
                            A powerful reminder that hope and happiness are choices we can make, even when life feels overwhelming.</p>
                    </div>
                    <div class="quote-section">
                        <h4>3. The Value of Friendship</h4>
                        <p>“Friendship is unnecessary, like philosophy, like art... It has no survival value; rather it is one of those things which give value to survival.” ~ C.S. Lewis
                            <br><br>
                            This beautifully captures the essence of human connection and the joys of meaningful relationships.</p>
                    </div>
                    
                    <h4>Visual Inspirations</h4>
                    <div id="overlay" class="overlay" onclick="closeOverlay()">
                        <img id="overlay-img" src="" alt="Expanded view">
                    </div>
                    <div class="image-gallery">
                        <img src="images/blog/b4.1.jpg" class="thumbnail" onclick="showOverlay(this)">
                        <img src="images/blog/b4.2.jpg" class="thumbnail" onclick="showOverlay(this)">
                        <img src="images/blog/b4.3.jpg" class="thumbnail" onclick="showOverlay(this)">
                    </div>
                    <div class="image-gallery" style="margin-top: 0px;">
                        <img src="images/blog/b4.4.jpg" class="thumbnail" onclick="showOverlay(this)">
                        <img src="images/blog/b4.5.jpg" class="thumbnail" onclick="showOverlay(this)">
                        <img src="images/blog/b4.6.jpg" class="thumbnail" onclick="showOverlay(this)">
                    </div>
                </div>
                <button onclick="toggleContent('content4', this)">CONTINUE READING</button>
            </div>
            
            <h1>19/03</h1>
        </div>
    </section>

    <?php
include 'include/footer.php';
?>

    <!-- javascript script file link -->
    <script src="assets/script.js" defer></script>
    <script>
        window.onload = function() {
            initializeCartCount(); }
            </script>
</body>

</html>