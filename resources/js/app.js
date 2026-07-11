import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

/**
 * Pemutar album. Setiap track bisa punya MP3 sendiri (array `audios`,
 * index sejajar dengan `tracks`). Bila track punya audio, progress bar
 * mengikuti durasi audio asli; bila tidak (atau file gagal dimuat),
 * pemutaran disimulasikan dengan timer.
 */
Alpine.data('albumPlayer', (tracks = [], audios = []) => ({
    tracks: tracks.length ? tracks : ['Track 1'],
    audios,
    playing: false,
    current: 0,
    progress: 0,
    _timer: null,

    get currentTitle() {
        return this.tracks[this.current] ?? '—';
    },

    get currentAudio() {
        return this.audios[this.current] || null;
    },

    get hasCurrentAudio() {
        return !!this.currentAudio;
    },

    toggle() {
        this.playing ? this.pause() : this.play();
    },

    play() {
        this.playing = true;
        const el = this.$refs.audio;

        if (this.currentAudio && el) {
            // Ganti sumber hanya bila berbeda supaya tidak restart tak perlu.
            if (el.getAttribute('src') !== this.currentAudio) {
                el.src = this.currentAudio;
            }
            this._stopTimer(); // progress digerakkan oleh event timeupdate audio
            const p = el.play();
            if (p && typeof p.catch === 'function') {
                // Autoplay ditolak / file tidak ada → jatuh ke simulasi.
                p.catch(() => { if (this.playing) this._simulate(); });
            }
            return;
        }

        // Track tanpa audio → simulasi.
        this._simulate();
    },

    pause() {
        this.playing = false;
        const el = this.$refs.audio;
        if (el) { try { el.pause(); } catch (e) {} }
        this._stopTimer();
    },

    select(i) {
        this.current = i;
        this.progress = 0;
        this._resetAudio();
        this.play();
    },

    next() {
        this.current = (this.current + 1) % this.tracks.length;
        this.progress = 0;
        this._resetAudio();
        if (this.playing) this.play();
    },

    prev() {
        this.current = (this.current - 1 + this.tracks.length) % this.tracks.length;
        this.progress = 0;
        this._resetAudio();
        if (this.playing) this.play();
    },

    // Progress mengikuti posisi audio nyata.
    onTimeUpdate() {
        const el = this.$refs.audio;
        if (el && el.duration && isFinite(el.duration)) {
            this.progress = Math.min(100, (el.currentTime / el.duration) * 100);
        }
    },

    // File audio gagal dimuat → tetap jalan sebagai simulasi.
    onAudioError() {
        if (this.playing && this.hasCurrentAudio) this._simulate();
    },

    _resetAudio() {
        const el = this.$refs.audio;
        if (el) { try { el.pause(); el.currentTime = 0; } catch (e) {} }
    },

    _simulate() {
        this._stopTimer();
        // Simulasi: ~12 detik per track.
        this._timer = setInterval(() => {
            if (!this.playing) return;
            this.progress += 0.7;
            if (this.progress >= 100) {
                this.progress = 0;
                this.next();
            }
        }, 90);
    },

    _stopTimer() {
        clearInterval(this._timer);
        this._timer = null;
    },
}));

Alpine.start();
