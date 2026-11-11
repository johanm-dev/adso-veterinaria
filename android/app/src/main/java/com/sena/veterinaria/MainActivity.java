package com.sena.veterinaria;

import android.annotation.SuppressLint;
import android.app.Activity;
import android.content.Intent;
import android.content.SharedPreferences;
import android.graphics.Bitmap;
import android.net.Uri;
import android.os.Bundle;
import android.os.Handler;
import android.os.Looper;
import android.util.Log;
import android.view.View;
import android.webkit.WebChromeClient;
import android.webkit.WebResourceError;
import android.webkit.WebResourceRequest;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.Button;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.ProgressBar;
import android.widget.Toast;

import java.net.HttpURLConnection;
import java.net.URL;
import java.util.ArrayList;
import java.util.List;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;

public class MainActivity extends Activity {

    private static final String TAG = "VetDigital";
    private static final String PREFS_NAME = "VetPrefs";
    private static final String KEY_SERVER_IP = "server_ip";
    private static final String DEFAULT_WIFI_IP = "192.168.137.6";
    private static final int PROBE_TIMEOUT_MS = 1800;

    private WebView webView;
    private ProgressBar progressBar;
    private LinearLayout layoutError;
    private EditText etIpServidor;
    private Button btnReintentar;

    private final ExecutorService executor = Executors.newSingleThreadExecutor();
    private final Handler mainHandler = new Handler(Looper.getMainLooper());

    @SuppressLint("SetJavaScriptEnabled")
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        webView = findViewById(R.id.webView);
        progressBar = findViewById(R.id.progressBar);
        layoutError = findViewById(R.id.layoutError);
        etIpServidor = findViewById(R.id.etIpServidor);
        btnReintentar = findViewById(R.id.btnReintentar);

        WebSettings webSettings = webView.getSettings();
        webSettings.setJavaScriptEnabled(true);
        webSettings.setDomStorageEnabled(true);
        webSettings.setDatabaseEnabled(true);
        webSettings.setUseWideViewPort(true);
        webSettings.setLoadWithOverviewMode(true);
        webSettings.setSupportZoom(false);
        webSettings.setBuiltInZoomControls(false);

        webView.setWebViewClient(new WebViewClient() {
            @Override
            public boolean shouldOverrideUrlLoading(WebView view, WebResourceRequest request) {
                String url = request.getUrl().toString();

                if (url.startsWith("https://api.whatsapp.com") || url.startsWith("whatsapp:") || url.startsWith("tel:")) {
                    try {
                        Intent intent = new Intent(Intent.ACTION_VIEW, Uri.parse(url));
                        startActivity(intent);
                        return true;
                    } catch (Exception e) {
                        Toast.makeText(MainActivity.this, "No se encontró la aplicación para abrir el enlace", Toast.LENGTH_SHORT).show();
                        return true;
                    }
                }

                view.loadUrl(url);
                return true;
            }

            @Override
            public void onPageStarted(WebView view, String url, Bitmap favicon) {
                super.onPageStarted(view, url, favicon);
                layoutError.setVisibility(View.GONE);
                webView.setVisibility(View.VISIBLE);
                progressBar.setVisibility(View.VISIBLE);
            }

            @Override
            public void onPageFinished(WebView view, String url) {
                super.onPageFinished(view, url);
                progressBar.setVisibility(View.GONE);
            }

            @Override
            public void onReceivedError(WebView view, WebResourceRequest request, WebResourceError error) {
                super.onReceivedError(view, request, error);
                if (request.isForMainFrame()) {
                    Log.e(TAG, "Error cargando URL: " + error.getDescription());
                    mostrarErrorConexion();
                }
            }
        });

        webView.setWebChromeClient(new WebChromeClient() {
            @Override
            public void onProgressChanged(WebView view, int newProgress) {
                super.onProgressChanged(view, newProgress);
                if (newProgress < 100 && layoutError.getVisibility() != View.VISIBLE) {
                    progressBar.setVisibility(View.VISIBLE);
                    progressBar.setProgress(newProgress);
                } else {
                    progressBar.setVisibility(View.GONE);
                }
            }
        });

        btnReintentar.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                String nuevaIp = etIpServidor.getText().toString().trim();
                if (!nuevaIp.isEmpty()) {
                    guardarIpServidor(nuevaIp);
                }
                conectarAutomaticamente(true);
            }
        });

        conectarAutomaticamente(false);
    }

    private void conectarAutomaticamente(final boolean priorizarGuardada) {
        layoutError.setVisibility(View.GONE);
        webView.setVisibility(View.VISIBLE);
        progressBar.setVisibility(View.VISIBLE);
        progressBar.setProgress(15);

        final String ipGuardada = obtenerIpGuardada();
        etIpServidor.setText(ipGuardada);

        executor.execute(new Runnable() {
            @Override
            public void run() {
                List<String> candidatos = new ArrayList<>();
                if (priorizarGuardada) {
                    candidatos.add(construirUrl(ipGuardada));
                }
                // 1. Túnel USB ADB (adb reverse tcp:8080 tcp:80)
                candidatos.add("http://127.0.0.1:8080/veterinaria/");
                // 2. IP guardada / Wi-Fi local actual
                String urlGuardada = construirUrl(ipGuardada);
                if (!candidatos.contains(urlGuardada)) {
                    candidatos.add(urlGuardada);
                }
                // 3. Emulador de Android Studio
                candidatos.add("http://10.0.2.2/veterinaria/");

                String urlDisponible = null;
                for (String candidato : candidatos) {
                    if (probarConexion(candidato)) {
                        urlDisponible = candidato;
                        break;
                    }
                }

                final String resultado = urlDisponible;
                mainHandler.post(new Runnable() {
                    @Override
                    public void run() {
                        if (isFinishing() || isDestroyed()) return;
                        if (resultado != null) {
                            Log.i(TAG, "Conectado a servidor: " + resultado);
                            webView.loadUrl(resultado);
                        } else {
                            Log.w(TAG, "Ningun servidor respondio");
                            mostrarErrorConexion();
                        }
                    }
                });
            }
        });
    }

    private boolean probarConexion(String urlStr) {
        HttpURLConnection conn = null;
        try {
            URL url = new URL(urlStr);
            conn = (HttpURLConnection) url.openConnection();
            conn.setConnectTimeout(PROBE_TIMEOUT_MS);
            conn.setReadTimeout(PROBE_TIMEOUT_MS);
            conn.setRequestMethod("GET");
            conn.setInstanceFollowRedirects(true);
            int code = conn.getResponseCode();
            Log.d(TAG, "Sondeo " + urlStr + " -> HTTP " + code);
            return code >= 200 && code < 400;
        } catch (Exception e) {
            Log.d(TAG, "Fallo sondeo " + urlStr + ": " + e.getMessage());
            return false;
        } finally {
            if (conn != null) {
                conn.disconnect();
            }
        }
    }

    private String obtenerIpGuardada() {
        SharedPreferences prefs = getSharedPreferences(PREFS_NAME, MODE_PRIVATE);
        return prefs.getString(KEY_SERVER_IP, DEFAULT_WIFI_IP);
    }

    private void guardarIpServidor(String ip) {
        SharedPreferences prefs = getSharedPreferences(PREFS_NAME, MODE_PRIVATE);
        prefs.edit().putString(KEY_SERVER_IP, ip).apply();
    }

    private String construirUrl(String ipOUrl) {
        String limpio = ipOUrl.trim();
        if (limpio.startsWith("http://") || limpio.startsWith("https://")) {
            return limpio.endsWith("/") ? limpio : limpio + "/";
        }
        return "http://" + limpio + "/veterinaria/";
    }

    private void mostrarErrorConexion() {
        progressBar.setVisibility(View.GONE);
        webView.setVisibility(View.GONE);
        etIpServidor.setText(obtenerIpGuardada());
        layoutError.setVisibility(View.VISIBLE);
    }

    @Override
    protected void onDestroy() {
        executor.shutdownNow();
        super.onDestroy();
    }

    @Override
    public void onBackPressed() {
        if (webView.getVisibility() == View.VISIBLE && webView.canGoBack()) {
            webView.goBack();
        } else {
            super.onBackPressed();
        }
    }
}
